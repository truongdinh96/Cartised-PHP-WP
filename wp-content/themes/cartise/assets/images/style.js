console.log("123");
(function (global) {
    'use strict';

    function G6k(options) {
        this.isDynamic = options.dynamic;
        this.isMobile = options.mobile;
        Date.setRegionalSettings(options);
        MoneyFunction.setRegionalSettings(options);
        this.locale = Date.locale;
        this.dateFormat = Date.format;
        this.inputDateFormat = Date.inputFormat;
        this.decimalPoint = MoneyFunction.decimalPoint;
        this.moneySymbol = MoneyFunction.moneySymbol;
        this.symbolPosition = MoneyFunction.symbolPosition;
        this.groupingSeparator = MoneyFunction.groupingSeparator;
        this.groupingSize = MoneyFunction.groupingSize;
        this.parser = new ExpressionParser();
        this.rulesengine = null;
        this.simu = null;
        this.currentProfil = null;
        this.variables = {};
        this.sourceRequestsQueue = [];
        this.sourceRequestRunning = false;
        this.sourceRequestsCache = {};
        this.lastUserInputName = "";
        this.lastSubmitBtn = null;
        this.hasFatalError = false;
        this.hasGlobalError = false;
        this.hasError = false;
        this.basePath = window.location.pathname.replace(/\/[^\/]+$/, "");
    };

    G6k.prototype = {
        run: function () {
            var self = this;
            this.variables['script'] = 1;
            $("div.help-panel dl dt").append('<a title="Fermer" href="javascript:">X</a>');
            $("div.help-panel dl dt a").click(function() {
                $(this).parents(".help-panel").parent().find('[data-toggle=collapse]').trigger('click');
            });
            $("input[type='reset'], button[type='reset']").click(function() {
                $('#g6k_form').clearForm();
                $("input.resettable").val("");
                if (self.isDynamic) {
                    self.variables = {};
                    $.each(self.simu.datas, function( name, data ) {
                        self.getData(name).modifiedByUser = false;
                        $("#" + name + ".output").text("");
                        self.resetDataValue(data);
                        self.removeError(name);
                        self.removeWarning(name);
                        if (typeof data.unparsedContent !== "undefined" && data.unparsedContent !== "") {
                            var content = self.evaluate(data.unparsedContent);
                            if (content !== false) {
                                if (content && data.type === "multichoice" && ! $.isArray(content)) {
                                    if (/\[\]$/.test(content)) {
                                        content = JSON.parse(content);
                                    } else {
                                        content = [content];
                                    }
                                } else if (content && (data.type === "money" || data.type === "percent")) {
                                    content = self.unFormatValue(content);
                                    content = parseFloat(content).toFixed(data.round || 2);
                                } else if (content && data.type === "number") {
                                    content = self.unFormatValue(content);
                                    if (data.round) {
                                        content = parseFloat(content).toFixed(data.round);
                                    }
                                }
                                data.value = content;
                                self.setVariable(name, data);
                            } else if (data.value !== '') {
                                data.value = '';
                                self.setVariable(name, data);
                            }
                        }
                        self.reevaluateFields(name);
                    });
                    self.removeGlobalError();
                    if ( $("div.foot-notes").children("div.foot-note").filter(":visible").length) {
                        $("div.foot-notes").show().removeAttr('aria-hidden');
                    } else {
                        $("div.foot-notes").attr('aria-hidden', true).hide();
                    }
                }
            });
            var collapseAllButton = $(".step-page .blockinfo-container .collapse-expand-all-tools button:first-child"),
                expandAllButton = $(".step-page .blockinfo-container .collapse-expand-all-tools button:last-child");
            collapseAllButton.on("click", function(e) {
                var scope = $(this).parents('.blockinfo-container');
                scope.find(".chapter-label > h3 > button.btn-collapse[aria-expanded=true]").trigger("click");
                e.stopPropagation();
                return false;
            });
            expandAllButton.on("click", function(e) {
                var scope = $(this).parents('.blockinfo-container');
                scope.find(".chapter-label > h3 > button.btn-collapse[aria-expanded=false]").trigger("click");
                e.stopPropagation();
                return false;
            });
            this.initializeWidgets();
            if (this.isDynamic) {
                var view = $('input[name=view]').eq(0).val();
                var step = $('input[name=step]').eq(0).val();
                var token = $('input[name=_csrf_token]').eq(0).val();
                var path = $(location).attr('pathname').replace("/"+view, "").replace(/\/+$/, "") + "/Default/fields";
                $.post(path,
                    {stepId: step, _csrf_token: token },
                    function(simu){
                        self.simu = simu;
                        self.processFields();
                        self.initializeExternalFunctions();
                    },
                    "json"
                ).fail(function(jqXHR, textStatus, errorThrown) {
                    if ((jqXHR.status != 0 && jqXHR.status != 200) || textStatus === 'timeout') {
                        self.setFatalError( Translator.trans("Data to continue this simulation are not accessible. Please try again later.") );
                    }
                });
            }
        },

        setProfile: function(profile) {
            var self = this;
            var id = profile.attr('data-profile-id');
            if (self.currentProfil == null || self.currentProfil.attr('data-profile-id') != id) {
                if (self.currentProfil != null) {
                    self.currentProfil.removeClass('active');
                }
                self.currentProfil = profile;
                profile.addClass('active');
                $.each(self.simu.profiles.profiles, function(p, profile) {
                    if (profile.id == id) {
                        $.each(profile.datas, function(d, data) {
                            self.setValue(data.name, data.default);
                        });
                    }
                });
            }
        },

        normalizeName: function(name) {
            if (/\[\]$/.test(name)) {
                name = name.substr(0, name.length - 2);
            }
            return name;
        },

        getData: function(name) {
            name = this.normalizeName(name);
            var data = this.simu.datas[name];
            return data;
        },

        getDataNameById: function(id) {
            var dataName = null;
            $.each(this.simu.datas, function(name, data) {
                if (data.id == id) {
                    dataName = name;
                    return false;
                }
            });
            return dataName;
        },

        getStep: function() {
            return this.simu.step;
        },

        getStepChildElement: function(parameters) {
            var element = this.simu.step.name;
            if (parameters.panel) {
                element += '-panel-' + parameters.panel;
                if (parameters.blockgroup) {
                    var blockinfo = element + '-blockinfo-' + parameters.blockgroup;
                    if ($('#' + blockinfo).length > 0) {
                        element = blockinfo;
                    } else {
                        element += '-fieldset-' + parameters.blockgroup;
                    }
                    element = document.getElementById(element);
                    if (element) {
                        element = element.parentElement;
                    }
                } else if (parameters.blockinfo) {
                    element += '-blockinfo-' + parameters.blockinfo;
                    if (parameters.chapter) {
                        element += '-chapter-' + parameters.chapter;
                        if (parameters.section) {
                            element += '-section-' + parameters.section;
                        } else if (parameters.content) {
                            element += '-section-' + parameters.content + '-content';
                        } else if (parameters.annotations) {
                            element += '-section-' + parameters.annotations + '-annotations';
                        }
                    }
                    element = document.getElementById(element);
                } else if (parameters.fieldset) {
                    element += '-fieldset-' + parameters.fieldset;
                    if (parameters.fieldrow) {
                        element += '-fieldrow-' + parameters.fieldrow;
                    }
                    if (parameters.field) {
                        var elementObj = $('#' + element).find("[data-field-position='" + parameters.field + "']");
                        element = elementObj[0];
                    } else if (parameters.prenote) {
                        var elementObj = $('#' + element).find("[data-field-position='" + parameters.prenote + "']");
                        element = elementObj.find('.pre-note')[0];
                    } else if (parameters.postnote) {
                        var elementObj = $('#' + element).find("[data-field-position='" + parameters.postnote + "']");
                        element = elementObj.find('.post-note')[0];
                    } else {
                        element = document.getElementById(element);
                    }
                } else {
                    element = document.getElementById(element);
                }
            } else if (parameters.footnote) {
                element = document.getElementById('foot-note-' + parameters.footnote);
            } else {
                element = document.getElementById(element);
            }
            return element;
        },

        isVisible: function (name) {
            var input = $("input[name='"+ name +"']");
            if (input.hasClass('listbox-input')) {
                input = input.parent();
            }
            return input.is(':visible');
        },

        check: function(data) {
            if (!data || !data.value || data.value.length == 0) {
                return true;
            }
            switch (data.type) {
                case 'date':
                    try {
                        var d = Date.createFromFormat(Date.inputFormat, data.value);
                    } catch (e) {
                        return false;
                    }
                    break;
                case 'money':
                    if (! /^-{0,1}\d+(\.\d{1,2})?$/.test(data.value)) {
                        return false;
                    }
                    break;
                case 'integer':
                    if (! /^\d+$/.test(data.value)) {
                        return false;
                    }
                    break;
                case 'number':
                case 'percent':
                    if (! /^-{0,1}\d*\.{0,1}\d+$/.test(data.value)) {
                        return false;
                    }
                    break;
                case 'text':
                    if (data.pattern) {
                        var re = new RegExp(data.pattern);
                        return re.test(data.value);
                    }
                    break;
            }
            return true;
        },

        resetMin: function(name) {
            var input = $(":input[name='" + name + "']");
            var data = this.getData(name);
            if (input.length > 0 && data.unparsedMin) {
                var min = this.evaluate(data.unparsedMin);
                if (min !== false) {
                    if (data.type == 'text' || data.type == 'textarea') {
                        min = parseInt(min, 10);
                        if (min) {
                            input.attr('minlength', min);
                        }
                    } else if (data.type == 'date') {
                        input.attr('min', min);
                    } else {
                        min = data.type == 'integer' ? parseInt(min, 10) : parseFloat(min);
                        if (min) {
                            input.attr('min', min);
                        }
                    }
                }
            }
        },

        checkMin: function(data) {
            if (!data || !data.value || data.value.length == 0) {
                return true;
            }
            if (data.type != 'number' && data.type != 'integer' && data.type != 'percent' && data.type != 'money' && data.type != 'date' && data.type != 'text' && data.type != 'textarea') {
                return true;
            }
            if (data.unparsedMin) {
                var min = this.evaluate(data.unparsedMin);
                if (min !== false) {
                    if (data.type == 'text' || data.type == 'textarea') {
                        min = parseInt(min, 10);
                        if (min && data.value.length < min) {
                            return false;
                        }
                    } else if (data.type == 'date') {
                        min = Date.createFromFormat(Date.inputFormat, min);
                        var val = Date.createFromFormat(Date.inputFormat, data.value);
                        if (val < min ) {
                            return false;
                        }
                    } else {
                        min = data.type == 'integer' ? parseInt(min, 10) : parseFloat(min);
                        var val  = data.type == 'integer' ? parseInt(data.value, 10) : parseFloat(data.value);
                        if (min && val < min ) {
                            return false;
                        }
                    }
                }
            }
            return true;
        },

        resetMax: function(name) {
            var input = $(":input[name='" + name + "']");
            var data = this.getData(name);
            if (input.length > 0 && data.unparsedMax) {
                var max = this.evaluate(data.unparsedMax);
                if (max !== false) {
                    if (data.type == 'text' || data.type == 'textarea') {
                        max = parseInt(max, 10);
                        if (max) {
                            input.attr('maxlength', max);
                        }
                    } else if (data.type == 'date') {
                        input.attr('max', max);
                    } else {
                        max = data.type == 'integer' ? parseInt(max, 10) : parseFloat(max);
                        if (max) {
                            input.attr('max', max);
                        }
                    }
                }
            }
        },

        checkMax: function(data) {
            if (!data || !data.value || data.value.length == 0) {
                return true;
            }
            if (data.type != 'number' && data.type != 'integer' && data.type != 'percent' && data.type != 'money' && data.type != 'date' && data.type != 'text' && data.type != 'textarea') {
                return true;
            }
            if (data.unparsedMax) {
                var max = this.evaluate(data.unparsedMax);
                if (max !== false) {
                    if (data.type == 'text' || data.type == 'textarea') {
                        max = parseInt(max, 10);
                        if (max && data.value.length > max) {
                            return false;
                        }
                    } else if (data.type == 'date') {
                        max = Date.createFromFormat(Date.inputFormat, max);
                        var val = Date.createFromFormat(Date.inputFormat, data.value);
                        if (val > max ) {
                            return false;
                        }
                    } else {
                        max = data.type == 'integer' ? parseInt(max, 10) : parseFloat(max);
                        var val  = data.type == 'integer' ? parseInt(data.value, 10) : parseFloat(data.value);
                        if (max && val > max) {
                            return false;
                        }
                    }
                }
            }
            return true;
        },

        validate: function(name) {
            var ok = true;
            name = this.normalizeName(name);
            var data = this.getData(name);
            if (data.inputField) {
                var field = this.simu.step.panels[data.inputField[0]].fields[data.inputField[1]];
                if (field.usage === 'input') {
                    this.removeError(name);
                    this.removeWarning(name);
                    if (!this.check(data)) {
                        ok = false;
                        switch (data.type) {
                            case 'date':
                                this.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans(Date.format) }, 'messages'));
                                break;
                            case 'number':
                                this.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("numbers only") }, 'messages'));
                                break;
                            case 'integer':
                                this.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("numbers only") }, 'messages'));
                                break;
                            case 'money':
                                this.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("amount") }, 'messages'));
                                break;
                            case 'percent':
                                this.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("percentage") }, 'messages'));
                                break;
                            default:
                                this.setError(name, Translator.trans("This value is not in the expected format"));
                        }
                    } else if (field.required && (!data.value || data.value.length == 0)) {
                        this.setError(name, Translator.trans("The '%field%' field is required",  { "field": field.label }, 'messages'));
                    } else if (field.visibleRequired && this.isVisible(name) && (!data.value || data.value.length == 0)) {
                        this.setError(name, Translator.trans("The '%field%' field is required",  { "field": field.label }, 'messages'));
                    } else if (!this.checkMin(data)) {
                        var min = this.evaluate(data.unparsedMin);
                        if (data.type == 'text' || data.type == 'textarea') {
                            this.setError(name, Translator.trans("The length of the field '%field%' cannot be less than %min%",  { "field": field.label, "min": min }, 'messages'));
                        } else {
                            this.setError(name, Translator.trans("The value of the field '%field%' cannot be less than %min%",  { "field": field.label, "min": min }, 'messages'));
                        }
                    } else if (!this.checkMax(data)) {
                        var max = this.evaluate(data.unparsedMax);
                        if (data.type == 'text' || data.type == 'textarea') {
                            this.setError(name, Translator.trans("The length of the field '%field%' cannot be greater than %max%",  { "field": field.label, "max": max }, 'messages'));
                        } else {
                            this.setError(name, Translator.trans("The value of the field '%field%' cannot be greater than %max%",  { "field": field.label, "max": max }, 'messages'));
                        }
                    }
                }
            }
            return ok;
        },

        setGlobalWarning: function(warning) {
            if (!$("#global-error").hasClass('has-error')) {
                $("#global-error").removeClass('hidden').addClass('has-warning').html(warning);
                $("#global-error").show().removeAttr('aria-hidden');
            }
        },

        removeGlobalWarning: function() {
            if (!$("#global-error").hasClass('has-error')) {
                $("#global-error").addClass('hidden').removeClass('has-warning').text("");
                $("#global-error").attr('aria-hidden', true).hide();
            }
        },

        setGroupWarning: function(name, warning) {
            var errorContainer = $("#"+name+"-error");
            if (! errorContainer.hasClass('has-error')) {
                errorContainer.removeClass('hidden').addClass('has-warning').html(warning);
                errorContainer.show().removeAttr('aria-hidden');
            }
        },

        removeGroupWarning: function(name) {
            var errorContainer = $("#"+name+"-error");
            if (! errorContainer.hasClass('has-error')) {
                errorContainer.addClass('hidden').removeClass('has-warning').text("");
                errorContainer.attr('aria-hidden', true).hide();
            }
        },

        setWarning: function(name, warning) {
            var self = this;
            var fieldContainer = $("#"+name+"-container");
            var visible = fieldContainer.is(':visible');
            $("input[name=" + name + "], input[type=checkbox], select[name=" + name + "]").each(function (index) {
                if ($(this).is(':checkbox')) {
                    var n = self.normalizeName($(this).attr('name'));
                    if (n != name) return true;
                }
                if (visible && !$(this).hasClass('has-error')) {
                    $(this).addClass('has-warning');
                    $(this).parent('.input-group').removeClass('hidden').addClass('has-warning');
                    $(this).focus();
                }
            });
            if (this.getData(name).datagroup) {
                this.setGroupWarning(this.getData(name).datagroup, warning);
            } else if (visible) {
                fieldContainer.find("div.field-error").last().removeClass('hidden').addClass('has-warning').html(warning);
                fieldContainer.show().removeAttr('aria-hidden');
                fieldContainer.parent().show().removeAttr('aria-hidden');
                this.hasWarning = true;
            }
        },

        removeWarning: function(name) {
            var self = this;
            if (this.getData(name).datagroup) {
                this.removeGroupWarning(this.getData(name).datagroup);
            } else {
                var fieldContainer = $("#"+name+"-container");
                fieldContainer.find("div.field-error").last().addClass('hidden').removeClass('has-warning').text("");
            }
            $("input[name=" + name + "], input[type=checkbox], select[name=" + name + "]").each(function (index) {
                if ($(this).is(':checkbox')) {
                    var n = self.normalizeName($(this).attr('name'));
                    if (n != name) return true;
                }
                $(this).removeClass('has-warning');
                $(this).parent('.input-group').removeClass('has-warning');
            });
        },

        setFatalError: function(error) {
            this.hasFatalError = true;
            this.hasGlobalError = true;
            this.hasError = true;
            $("#global-error").addClass("fatal-error");
            $("#g6k_form input, #g6k_form select, #g6k_form textarea" ).prop( "disabled", true );
            var errorhtml = "";
            if ($.isArray(error)) {
                errorhtml = '<p>' + error.join('</p><p>') + '</p>';
            } else {
                errorhtml = '<p>' + error + '</p>';
            }
            $("#global-error").removeClass('hidden').addClass('has-error').html(errorhtml);
            $("#global-error").show().removeAttr('aria-hidden');
        },

        setGlobalError: function(error) {
            this.hasGlobalError = true;
            this.hasError = true;
            var errorhtml = "";
            if ($.isArray(error)) {
                errorhtml = '<p>' + error.join('</p><p>') + '</p>';
            } else {
                errorhtml = '<p>' + error + '</p>';
            }
            $("#global-error").removeClass('hidden').addClass('has-error').html(errorhtml);
            $("#global-error").show().removeAttr('aria-hidden');
        },

        removeGlobalError: function() {
            $("#g6k_form input, #g6k_form select, #g6k_form textarea" ).prop( "disabled", false );
            $("#global-error").addClass('hidden').removeClass('has-error').text("");
            $("#global-error").attr('aria-hidden', true).hide();
            this.hasGlobalError = false;
        },

        setGroupError: function(name, error) {
            this.hasError = true;
            var errorContainer = $("#"+name+"-error");
            var errorhtml = "";
            if ($.isArray(error)) {
                errorhtml = '<p>' + error.join('</p><p>') + '</p>';
            } else {
                errorhtml = '<p>' + error + '</p>';
            }
            errorContainer.removeClass('hidden').addClass('has-error').html(errorhtml);
            errorContainer.show().removeAttr('aria-hidden');
        },

        removeGroupError: function(name) {
            var errorContainer = $("#"+name+"-error");
            errorContainer.addClass('hidden').removeClass('has-error').text("");
            errorContainer.attr('aria-hidden', true).hide();
        },

        setError: function(name, error) {
            var self = this;
            var fieldContainer = $("#"+name+"-container");
            var visible = fieldContainer.is(':visible');
            $("input[name=" + name + "], input[type=checkbox], select[name=" + name + "]").each(function (index) {
                if ($(this).is(':checkbox')) {
                    var n = self.normalizeName($(this).attr('name'));
                    if (n != name) return true;
                }
                if (visible) {
                    $(this).addClass('has-error');
                    if (self.getData(name).datagroup) {
                        $(this).attr('aria-describedby', self.getData(name).datagroup + '-error');
                    } else {
                        $(this).attr('aria-describedby', name + '-field-error');
                    }
                    $(this).parent('.input-group').removeClass('hidden').addClass('has-error');
                    $(this).attr('aria-invalid', true);
                    $(this).focus();
                }
            });
            if (this.getData(name).datagroup) {
                this.setGroupError(this.getData(name).datagroup, error);
            } else if (visible) {
                var errorhtml = "";
                if ($.isArray(error)) {
                    errorhtml = '<p>' + error.join('</p><p>') + '</p>';
                } else {
                    errorhtml = '<p>' + error + '</p>';
                }
                fieldContainer.find("div.field-error").last().removeClass('hidden').addClass('has-error').html(errorhtml);
                fieldContainer.show().removeAttr('aria-hidden');
                fieldContainer.parent().show().removeAttr('aria-hidden');
                this.hasError = true;
            }
        },

        removeError: function(name) {
            var self = this;
            if (this.getData(name).datagroup) {
                this.removeGroupError(this.getData(name).datagroup);
            } else {
                var fieldContainer = $("#"+name+"-container");
                fieldContainer.find("div.field-error").last().addClass('hidden').removeClass('has-error').text("");
            }
            $("input[name=" + name + "], input[type=checkbox], select[name=" + name + "]").each(function (index) {
                if ($(this).is(':checkbox')) {
                    var n = self.normalizeName($(this).attr('name'));
                    if (n != name) return true;
                }
                $(this).removeClass('has-error');
                $(this).removeAttr('aria-describedby')
                $(this).parent('.input-group').removeClass('has-error');
                if (this.hasAttribute('type') && $(this).attr('type') == 'number') {
                    $(this).removeAttr('aria-invalid');
                } else {
                    $(this).attr('aria-invalid', false);
                }
            });
        },

        setFormValue: function(name, data) {
            var self = this;
            if (data.type === "multichoice") {
                $("input[type=checkbox]").each(function (index) {
                    var n = self.normalizeName($(this).attr('name'));
                    if (n == name) {
                        if ($.inArray($(this).val(), data.value)) {
                            if (! $(this).is(':checked')) $(this).prop('checked', true);
                        } else {
                            if ($(this).is(':checked')) $(this).prop('checked', false);
                        }
                    }
                });
                return;
            }
            $("input[name=" + name + "], select[name=" + name + "], span[id=" + name + "]").each(function (index) {
                if ($(this).is('span')) {
                    $(this).text(self.formatValue(data));
                } else if ($(this).is('select')) {
                    if ($(this).val() != data.value) $(this).val(data.value);
                } else if ($(this).is(':radio')) {
                    $(this).val([data.value]);
                    $(this).parent('label').parent('fieldset').find('label.choice').removeClass('checked');
                    if ( $(this).is(':checked') ) {
                        $(this).parent('label').addClass('checked');
                    }
                } else if ($(this).is(':checkbox')) {
                    if ($(this).val() != data.value) $(this).val(data.value);
                } else if ($(this).hasClass('listbox-input')) {
                    if ($(this).val() != data.value) {
                        $(this).val(data.value);
                        $(this).listbox('update');
                    }
                } else {
                    if ($(this).val() != data.value) $(this).val(data.value);
                }
            });
        },

        resetDataValue: function (data) {
            if (data.type === "multichoice") {
                data.value = [];
            } else {
                data.value = "";
            }
        },

        unsetChoiceValue: function(name, value) { // only for type = 'multichoice'
            var data = this.getData(name);
            if (value && data && data.type === "multichoice" && ! $.isArray(value)) {
                var ovalues = data.value ? data.value : [];
                var pos = $.inArray(value, ovalues);
                if (pos >= 0) {
                    ovalues.splice( pos, 1 );
                    data.value = ovalues;
                    this.setVariable(name, data);
                    this.validate(name);
                    if (this.simu.memo && this.simu.memo == "1" && data.memorize && data.memorize == "1") {
                        if (! $.cookie(name) || $.cookie(name) != value) {
                            $.cookie(name, value, { expires: 365, path: this.basePath });
                        }
                    }
                    this.lastUserInputName = "";
                    this.reevaluateFields(name);
                }
            }
        },

        unsetValue: function(name) {
            var self = this;
            var data = self.getData(name);
            if (data.value !== '') {
                setTimeout(function(){ self.setValue(name, ''); }, 0);
            }
        },

        setValue: function(name, value) {
            var self = this;
            var data = self.getData(name);
            if (($.isArray(value) || $.isPlainObject(value)) && data.type != "array" && data.type != "multichoice") {
                var avalue = value;
                value = "";
                $.each(avalue, function(key, val) {
                    value = val;
                    return false;
                });
            }
            if (value && (data.type === "money" || data.type === "percent")) {
                value = self.unFormatValue(value);
                value = parseFloat(value).toFixed(data.round || 2);
            } else if (value && (data.type === "number")) {
                value = self.unFormatValue(value);
                if (data.round) {
                    value = parseFloat(value).toFixed(data.round);
                }
            } else if (value && data.type === "multichoice" && ! $.isArray(value)) {
                if (/\[\]$/.test(value)) {
                    value = JSON.parse(value);
                } else {
                    var ovalues = data.value ? data.value : [];
                    ovalues.push(value);
                    value = ovalues;
                }
            }
            data.value = value;
            self.setVariable(name, data);
            self.validate(name);
            if (name !== self.lastUserInputName || data.type === "integer" || data.type === "number" || data.type === "date") {
                self.setFormValue(name, data);
            }
            if (self.simu.memo && self.simu.memo == "1" && data.memorize && data.memorize == "1") {
                if (! $.cookie(name) || $.cookie(name) != value) {
                    $.cookie(name, value, { expires: 365, path: self.basePath });
                }
            }
            self.lastUserInputName = "";
            self.reevaluateFields(name);
        },

        setVariable: function (name, data) {
            this.variables[name] = data.value;
            if (! data.value && data.deflt) {
                this.variables[name] = data.deflt;
            }
        },

        evaluate: function (expression) {
            var expr = this.parser.parse(expression);
            expr.postfix();
            expr.setVariables(this.variables);
            return expr.evaluate();
        },

        evaluateDefaults: function() {
            var self = this;
            $.each(self.simu.datas, function( name, data ) {
                if (typeof data.unparsedDefault !== "undefined" && data.unparsedDefault !== "") {
                    var value = self.evaluate(data.unparsedDefault);
                    if (value !== false) {
                        data.deflt = value;
                    }
                }
            });
        },

        reevaluateFields: function (name) {
            var self = this;
            var data = this.getData(name);
            if (typeof data.unparsedExplanation !== "undefined" && data.unparsedExplanation !== "") {
                var explanation = this.evaluate(data.unparsedExplanation);
                if (explanation === false) {
                    $("#" + name + "-explanation").text("");
                } else {
                    $("#" + name + "-explanation").html(explanation);
                }
            }
            if (data.defaultDependencies) {
                $.each(data.defaultDependencies, function( d, dependency ) {
                    var field = self.getData(dependency);
                    if (typeof field.unparsedDefault !== "undefined" && field.unparsedDefault !== "") {
                        var value = self.evaluate(field.unparsedDefault);
                        if (value !== false) {
                            field.deflt = value;
                        }
                    }
                });
            }
            if (data.minDependencies) {
                $.each(data.minDependencies, function( d, dependency ) {
                    var field = self.getData(dependency);
                    if (field.unparsedMin !== "undefined" && field.unparsedMin !== "") {
                        self.resetMin(dependency);
                    }
                });
            }
            if (data.maxDependencies) {
                $.each(data.maxDependencies, function( d, dependency ) {
                    var field = self.getData(dependency);
                    if (field.unparsedMax !== "undefined" && field.unparsedMax !== "") {
                        self.resetMax(dependency);
                    }
                });
            }
            if (data.indexDependencies) {
                $.each(data.indexDependencies, function( d, dependency ) {
                    var field = self.getData(dependency);
                    if (field.unparsedIndex !== "undefined" && field.unparsedIndex !== "") {
                        self.reevaluateFields(dependency);
                    }
                });
            }
            if (data.contentDependencies) {
                $.each(data.contentDependencies, function( d, dependency ) {
                    var field = self.getData(dependency);
                    if ((! field.modifiedByUser || field.value === '') && typeof field.unparsedContent !== "undefined" && field.unparsedContent !== "") {
                        var content = self.evaluate(field.unparsedContent);
                        if (content !== false) {
                            if (content && field.type === "multichoice" && ! $.isArray(content)) {
                                if (/\[\]$/.test(content)) {
                                    content = JSON.parse(content);
                                } else {
                                    content = [content];
                                }
                            }
                            if (field.value !== content) {
                                self.setValue(dependency, content);
                            }
                        } else {
                            self.unsetValue(dependency);
                        }
                    }
                });
            }
            if (data.noteDependencies) {
                $.each(data.noteDependencies, function( d, dependency ) {
                    var datad = self.getData(dependency);
                    if (datad.inputField) {
                        var field = self.simu.step.panels[datad.inputField[0]].fields[datad.inputField[1]];
                        if (field.prenote) {
                            var prenote = self.replaceVariables(field.prenote);
                            if (prenote !== false) {
                                var id = '#' + dependency + '-container .pre-note';
                                var oldNote = $(id).html();
                                if (prenote != oldNote) {
                                    $(id).html(prenote);
                                    $(id).attr('aria-live', 'polite');
                                } else {
                                    $(id).removeAttr('aria-live');
                                }
                            }
                        }
                        if (field.postnote) {
                            var postnote = self.replaceVariables(field.postnote);
                            if (postnote !== false) {
                                var id = '#' + dependency + '-container .post-note';
                                var oldNote = $(id).html();
                                if (postnote != oldNote) {
                                    $(id).html(postnote);
                                    $(id).attr('aria-live', 'polite');
                                } else {
                                    $(id).removeAttr('aria-live');
                                }
                            }
                        }
                    }
                });
            }
            if (data.sectionContentDependencies) {
                $.each(data.sectionContentDependencies, function( d, dependency ) {
                    var sectionId = dependency;
                    var chapterId = dependency.replace(/-section-.*$/, '');
                    var blockinfoId = dependency.replace(/-chapter-.*$/, '');
                    var content = self.simu.step.panels[blockinfoId].chapters[chapterId].sections[sectionId].content;
                    var newcontent = self.replaceVariablesOrBlank(content);
                    var id = '#' + sectionId + '-content';
                    var oldContent = $(id).html();
                    if (newcontent != oldContent) {
                        $(id).html(newcontent);
                        $(id).attr('aria-live', 'polite');
                    } else {
                        $(id).removeAttr('aria-live');
                    }
                });
            }
            if (data.footNoteDependencies) {
                $.each(data.footNoteDependencies, function( d, dependency ) {
                    var footnote = self.simu.step.footnotes[dependency];
                    var footnotetext = self.replaceVariables(footnote.text);
                    if (footnotetext !== false) {
                        var id = "#foot-note-" + dependency;
                        var oldNote = $(id).html();
                        if (footnotetext != oldNote) {
                            $(id).html(footnotetext);
                            $(id).attr('aria-live', 'polite');
                        } else {
                            $(id).removeAttr('aria-live');
                        }
                    }
                });
                if ( $("div.foot-notes").children("div.foot-note").has(":visible")) {
                    self.showObjectLater($("div.foot-notes"));
                } else {
                    self.hideObject($("div.foot-notes"));
                }
            }
            if (data.sourceDependencies) {
                $.each(data.sourceDependencies, function( d, dependency ) {
                    var completed = true;
                    var params = self.simu.sources[dependency]['parameters'];
                    $.each(params, function( p, param ) {
                        if (param.origin === 'data' && param.optional == '0') {
                            var d = self.getData(param.data);
                            if (typeof d.value === "undefined" || d.value === "") {
                                completed = false;
                                return false;
                            } else if ((d.type == 'text' || d.type == 'textarea') && d.unparsedMin) {
                                var min = self.evaluate(d.unparsedMin);
                                if (min === false || d.value.length < parseInt(min, 10)) {
                                    completed = false;
                                    return false;
                                }
                            }
                        }
                    });
                    if (completed) {
                        var type = self.simu.sources[dependency]['datasource']['type'];
                        var returnType = self.simu.sources[dependency]['returnType'];
                        if (type === 'uri' && (returnType === 'json' || returnType === 'csv' || (document.evaluate && (returnType === 'xml'|| returnType === 'html')))) {
                            self.getUriSource(dependency);
                        } else {
                            self.getInternalSource(dependency);
                        }
                    } else {
                        self.resetSourceDatas(dependency);
                        self.populateChoiceDependencies(dependency, []);
                    }
                });
            }
            if (data.rulesConditionsDependency) {
                $.each(data.rulesConditionsDependency, function(r) {
                    self.rulesengine.run(
                        data.rulesConditionsDependency[r] - 1,
                        self.variables,
                        function(err, result) {
                            if (err) {  }
                        }
                    );
                });
            }
            if (data.rulesActionsDependency) {
                $.each(data.rulesActionsDependency, function(r) {
                    self.rulesengine.run(
                        data.rulesActionsDependency[r] - 1,
                        self.variables,
                        function(err, result) {
                            if (err) {  }
                        }
                    );
                });
            }
        },

        formatParamValue: function (param) {
            var data = this.getData(param.data);
            if (typeof data.value === "undefined" || data.value === "") {
                return null;
            }
            var value = data.value;
            switch (data.type) {
                case "date":
                    var format = param.format;
                    if (format != "" && value != "") {
                        var date = Date.createFromFormat(Date.inputFormat, value);
                        value = date.format(format);
                    }
                    break;
                case "day":
                    var format = param.format;
                    if (format != "" && value != "") {
                        var date = Date.createFromFormat("j/n/Y", value + "/1/2015");
                        value = date.format(format);
                    }
                    break;
                case "month":
                    var format = param.format;
                    if (format != "" && value != "") {
                        var date = Date.createFromFormat("j/n/Y", "1/" + value + "/2015");
                        value = date.format(format);
                    }
                    break;
                case "year":
                    var format = param.format;
                    if (format != "" && value != "") {
                        var date = Date.createFromFormat("j/n/Y", "1/1/" + value);
                        value = date.format(format);
                    }
                    break;
            }
            return value;
        },

        str_getcsv: function(input, delimiter, enclosure, escape) {
            // Thanks to Locutus
            // https://github.com/kvz/locutus/blob/master/src/php/strings/str_getcsv.js
            var output = [];
            var _backwards = function (str) {
                return str.split('').reverse().join('');
            }
            var _pq = function (str) {
                return String(str).replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}=!<>\|:])/g, '\\$1')
            }
            delimiter = delimiter || ',';
            enclosure = enclosure || '"';
            escape = escape || '\\';
            var pqEnc = _pq(enclosure);
            var pqEsc = _pq(escape);
            input = input.replace(new RegExp('^\\s*' + pqEnc), '').replace(new RegExp(pqEnc + '\\s*$'), '');
            input = _backwards(input).split(new RegExp(pqEnc + '\\s*' + _pq(delimiter) + '\\s*' + pqEnc + '(?!' + pqEsc + ')', 'g')).reverse();
            for (var i = 0, inpLen = input.length; i < inpLen; i++) {
                output.push(_backwards(input[i]).replace(new RegExp(pqEsc + pqEnc, 'g'), enclosure));
            }
            return output;
        },

        xmlToObject: function (node) {
            switch (node.nodeType) {
                case 9: // document
                case 1: // element
                    var object = {};
                    var attributes = node.attributes;
                    for (var a = 0; a < attributes.length; a++) {
                        var attr = attributes.item(a);
                        object[attr.name] = attr.value;
                    }
                    var children = node.childNodes;
                    var hasChildOrAttributes = node.attributes.length > 0;
                    var text = '';
                    if (! hasChildOrAttributes) {
                        for (var c = 0; c < children.length; c++) {
                            var child = children.item(c);
                            if (child.nodeType == 3) {
                                text += child.nodeValue;
                            } else if (child.nodeType == 1 || child.nodeType == 2) {
                                hasChildOrAttributes = true;
                                break;
                            }
                        }
                    }
                    var nodeObj = {};
                    if (! hasChildOrAttributes) {
                        nodeObj[node.nodeName] = text;
                    } else {
                        for (var c = 0; c < children.length; c++) {
                            var child = children.item(c);
                            var childObj = self.xmlToObject(child);
                            if (childObj != null) {
                                object[child.nodeName] = childObj;
                            }
                        }
                        nodeObj[node.nodeName] = object;
                    }
                    return nodeObj;
                case 2: // attribute
                    var object = {};
                    object[node.name] = node.value;
                    return object;
                case 3: // text
                    return node.nodeValue;
                default:
                    return null;
            }
        },

        getUriSource: function (source) {
            var self = this;
            var path = '';
            var query = '';
            var headers = [];
            var datas = {};
            var ok = true;
            var params = self.simu.sources[source]['parameters'];
            $.each(params, function( p, param ) {
                var value;
                if (param.origin == 'data') {
                    value = self.formatParamValue(param);
                } else {
                    value = param.constant;
                }
                if (value == null) {
                    if (param.optional == '0') {
                        ok = false;
                        return false;
                    }
                    value = '';
                }
                if (param.type == 'path') {
                    if (value != '' || param.optional == '0') {
                        path += "/" + value.replace(/\s+/g, '+');
                    }
                } else if (param.type == 'data') {
                    var name = param.name;
                    if (datas[name]) {
                        datas[name].push(value);
                    } else {
                        datas[name] = [value];
                    }
                    query += '&' + encodeURI(name) + '=' + encodeURI(value);
                } else if (param.type == 'header') {
                    if (value != '') {
                        headers.push({ name: param.name, value: value });
                    }
                } else if (value != '' || param.optional == '0') {
                    datas[param.name] = value;
                    query += '&' + encodeURI(param.name) + '=' + encodeURI(value);
                }
            });
            if (! ok) {
                return null;
            }
            var uri = self.simu.sources[source]['datasource']['uri'];
            if (path != "") {
                uri += encodeURI(path);
            }
            if (query != '') {
                query = uri + '?' + query.substr(1);
            }
            var method = self.simu.sources[source]['datasource']['method'];
            var returnType = self.simu.sources[source]['returnType'];
            self.enqueueSourceRequest(source, method.toUpperCase(), uri, datas, returnType, headers,
                function (source, returnType, result) {
                    var returnPath = self.simu.sources[source]['returnPath'];
                    returnPath = self.replaceVariables(returnPath);
                    if (returnType == 'json') {
                        if (returnPath != '') {
                            if (/^\\$/.test(returnPath)) { // jsonpath
                                result = JSONPath({path: returnPath, json: result});
                            } else { // xpath
                                result = defiant.json.search(result, returnPath);
                                if ($.isArray(result) && result.length == 1) {
                                    result = result[0];
                                }
                            }
                        }
                    } else if (returnType == 'csv') {
                        var separator = self.simu.sources[source]['separator'];
                        var delimiter = self.simu.sources[source]['delimiter'];
                        var lines = result.split(/\n/);
                        result = [];
                        for (var l = 0; l < lines.length; l++) {
                            var line = $.trim(lines[l]);
                            if (line != '') {
                                var csv = self.str_getcsv(line, separator, delimiter);
                                var cols = $.map(csv, function (c) {
                                    return $.trim(c);
                                });
                                result.push(cols);
                            }
                        }
                        if (returnPath) {
                            var indices = returnPath.split("/");
                            $.each(indices, function (i, index) {
                                result = result[parseInt(index, 10) - 1];
                            });
                        }
                    } else if (returnType == 'xml'|| returnType == 'html') {
                        result = extractXMLResult(result, returnPath);
                    }
                    self.processSource(source, result, returnType);
                },
                function(source, returnType, result) {
                    self.resetSourceDatas(source);
                    self.populateChoiceDependencies(source, []);
                }
            );
        },

        extractXMLResult: function (result, returnPath) {
            var snapshot = document.evaluate(returnPath, $(result).get(0), null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
            result = [];
            try {
                for (var i = 0, len = snapshot.snapshotLength; i < len; i++) {
                    var node = snapshot.snapshotItem(i);
                    switch (node.nodeType) {
                        case 9: // document
                        case 1: // element
                            result.push(self.xmlToObject(node));
                            break;
                        case 2: // attribute
                            var object = {};
                            object[node.name] = node.value;
                            result.push(object);
                            break;
                        case 3: // text
                            result.push(node.nodeValue);
                    }
                }
            }
            catch (e) {
            }
            return result;
        },

        getInternalSource: function (source) {
            var self = this;
            var post = {};
            post['source'] = source;
            var returnPath = self.simu.sources[source]['returnPath'];
            var replacedPath = self.replaceVariables(returnPath);
            if (replacedPath != returnPath) {
                post['returnPath'] = replacedPath;
            }
            var params = self.simu.sources[source]['parameters'];
            $.each(params, function( p, param ) {
                if (param.origin === 'data') {
                    var d = self.getData(param.data);
                    if (typeof d.value !== "undefined" && d.value !== "") {
                        post[param.name] = d.value;
                    }
                } else if (param.origin === 'constant') {
                    post[param.name] = param.constant;
                }
            });
            var view = $('input[name=view]').eq(0).val();
            var token = $('input[name=_csrf_token]').eq(0).val();
            if (token) {
                post['_csrf_token'] = token;
            }
            var path = $(location).attr('pathname').replace("/"+view, "").replace(/\/+$/, "") + "/Default/source";
            self.enqueueSourceRequest(source, 'POST', path, post, 'json',[],
                function (source, returnType, result) {
                    self.processSource(source, result, 'assocArray');
                },
                function(source, returnType, result) {
                    self.resetSourceDatas(source);
                    self.populateChoiceDependencies(source, []);
                }
            );

        },

        enqueueSourceRequest: function(source, method, uri, data, returnType, headers, success, error) {
            var self = this;

            self.sourceRequestsQueue.push({
                source: source,
                method: method,
                uri: uri,
                data: data,
                returnType: returnType,
                headers: headers,
                success: success,
                error: error
            });

            function runSourceRequest() {
                if (self.sourceRequestRunning) {
                    return;
                }
                if (self.sourceRequestsQueue.length > 0) {
                    self.sourceRequestRunning = true;
                    var q = self.sourceRequestsQueue.shift();
                    var key = q.uri + '?' + $.param(q.data);
                    if (self.sourceRequestsCache[key]) {
                        if (self.sourceRequestsCache[key]['error']) {
                            q.error.call(self, q.source, "json", self.sourceRequestsCache[key]);
                        } else {
                            q.success.call(self, q.source, q.returnType, self.sourceRequestsCache[key]);
                        }
                        self.sourceRequestRunning = false;
                        runSourceRequest();
                    } else {
                        $.ajax({
                            method: q.method,
                            url: q.uri,
                            dataType: q.returnType,
                            data: q.data,
                            beforeSend: function(xhr){
                                $.each(q.headers, function(h, header) {
                                    xhr.setRequestHeader(header.name, header.value);
                                });
                            }
                        }).done(function( result ) {
                            self.sourceRequestsCache[key] = result;
                            q.success.call(self, q.source, q.returnType, result);
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            if ((jqXHR.status != 0 && jqXHR.status >= 500) || textStatus === 'timeout') {
                                self.setFatalError( Translator.trans("Data to continue this simulation are not accessible. Please try again later.") );
                            } else {
                                var result = { 'error': jqXHR.status};
                                self.sourceRequestsCache[key] = result;
                                q.error.call(self, q.source, "json", result);
                            }
                        }).always(function() {
                            self.sourceRequestRunning = false;
                            runSourceRequest();
                        });
                    }
                }
            }

            runSourceRequest();
        },

        processSource: function(source, result, returnType) {
            var self = this;
            $.each(this.simu.datas, function( name, data ) {
                if (typeof data.unparsedSource !== "undefined" && data.unparsedSource !== "") {
                    var s = self.evaluate(data.unparsedSource);
                    if (s == source) {
                        if (typeof data.unparsedIndex !== "undefined" && data.unparsedIndex !== "") {
                            var index;
                            if (returnType == 'assocArray') {
                                index = self.evaluate(data.unparsedIndex);
                            } else {
                                index = data.unparsedIndex.replace(/^'/, '').replace(/'$/, '');
                                index = self.replaceVariables(index);
                            }
                            if (index !== false) {
                                var value = result;
                                if (returnType == 'assocArray') {
                                    if (value[index]) {
                                        self.setValue(name, value[index]);
                                    } else {
                                        self.setValue(name, value[index.toLowerCase()]);
                                    }
                                } else if (returnType == 'json') {
                                    if (index != '') {
                                        if (/^\\$/.test(index)) { // jsonpath
                                            value = JSONPath({path: index, json: value});
                                        } else { // xpath
                                            value = defiant.json.search(value, index);
                                            if ($.isArray(value) && value.length == 1) {
                                                value = value[0];
                                            }
                                        }
                                    }
                                    self.setValue(name, value);
                                } else if (returnType == 'csv') {
                                    var indices = index.split("/");
                                    $.each(indices, function (i, ind) {
                                        value = value[parseInt(ind, 10) - 1];
                                    });
                                    self.setValue(name, value);
                                } else if (returnType == 'xml'|| returnType == 'html') {
                                    value = extractXMLResult(value, index);
                                    if ($.isArray(value) && value.length == 1) {
                                        value = value[0];
                                    }
                                    self.setValue(name, value);
                                }
                            } else {
                                self.unsetValue(name);
                            }
                        } else {
                            self.setValue(name, result);
                        }
                    }
                }
            });
            this.populateChoiceDependencies(source, result);
        },

        resetSourceDatas: function(source) {
            var self = this;
            $.each(this.simu.datas, function( name, data ) {
                if (typeof data.unparsedSource !== "undefined" && data.unparsedSource !== "") {
                    var s = self.evaluate(data.unparsedSource);
                    if (s == source) {
                        self.unsetValue(name);
                    }
                }
            });
        },

        populateChoiceDependencies : function (source, result) {
            var self = this;
            var dependencies = this.simu.sources[source]['choiceDependencies'];
            if (dependencies) {
                $.each(dependencies, function( d, dependency ) {
                    var valueColumn = self.getData(dependency).choices.source.valueColumn;
                    var labelColumn = self.getData(dependency).choices.source.labelColumn;
                    var choice = $("#"+dependency);
                    if (choice.is('select')) {
                        choice.empty();
                        var options = ['<option value="">-----</option>'];
                        for (var r in result) {
                            var row = result[r];
                            options.push('<option value="', row[valueColumn] || row[valueColumn.toLowerCase()], '">', row[labelColumn] || row[labelColumn.toLowerCase()], '</option>');
                        }
                        choice.html(options.join(''));
                    } else if (choice.hasClass('listbox-input')) {
                        var items = [];
                        items.push({ value: "", text: "-----", selected: true});
                        for (var r in result) {
                            var row = result[r];
                            items.push({ value: row[valueColumn] || row[valueColumn.toLowerCase()], text: row[labelColumn] || row[labelColumn.toLowerCase()] });
                        }
                        choice.listbox('setItems', items);
                    }
                    self.setValue(dependency, "");
                });
            }
        },

        validateAll: function() {
            var self = this;
            var ok = true;
            this.hasError = false;
            $.each(this.simu.datas, function( name, data ) {
                ok = self.validate(name) && ok;
            });
            if (ok) this.rulesengine.runAll(this.variables,
                function(err, result) {
                    if (err) {
                    }
                }
            );
            return ok && !this.hasError;
        },

        processFields: function () {
            this.variables['script'] = 1;
            this.variables['dynamic'] = 1;

            this.evaluateDefaults();
            var self = this;
            $("#g6k_form input[type!=checkbox][type!=radio][name], #g6k_form input:radio:checked[name], #g6k_form input:checkbox:checked[name], #g6k_form select[name], #g6k_form textarea[name]").each(function() {
                var name = self.normalizeName($(this).attr('name'));
                var data = self.getData(name);
                if (data) {
                    var value = $(this).val();
                    if (value && (data.type === "money" || data.type === "percent" || data.type === "number")) {
                        value = self.unFormatValue(value);
                    }
                    if (data.type === 'multichoice') {
                        if ($(this).attr('type') === 'checkbox') {
                            var ovalues = self.variables[name] || [];
                            ovalues.push(value);
                            value = ovalues;
                        } else if (/^\[.*\]$/.test(value)) {
                            value = JSON.parse(value);
                        }
                    }
                    self.variables[name] = value;
                }
            });

            var rulesData = [];
            $.each(this.simu.rules, function(r, rule) {
                rulesData.push(
                    {
                        conditions: rule.conditions,
                        ifActions: rule.ifdata,
                        elseActions: rule.elsedata
                    }
                );
            });
            var actionsAdapter = {
                notifyError: function(data) {
                    var errorMessage = data.find("message");
                    var target = data.find("target");
                    switch (target) {
                        case 'data':
                            var fieldName = data.find("target", "fieldName");
                            self.setError(fieldName, self.replaceVariables(errorMessage));
                            break;
                        case 'datagroup':
                            var datagroupName = data.find("target", "datagroupName");
                            self.setGroupError(datagroupName, self.replaceVariables(errorMessage));
                            break;
                        case 'dataset':
                            self.setGlobalError(self.replaceVariables(errorMessage));
                            break;
                    }
                },
                notifyWarning: function(data) {
                    var warningMessage = data.find("message");
                    var target = data.find("target");
                    switch (target) {
                        case 'data':
                            var fieldName = data.find("target", "fieldName");
                            self.setWarning(fieldName, self.replaceVariables(warningMessage));
                            break;
                        case 'datagroup':
                            var datagroupName = data.find("target", "datagroupName");
                            self.setGroupWarning(datagroupName, self.replaceVariables(warningMessage));
                            break;
                        case 'dataset':
                            self.setGlobalWarning(self.replaceVariables(warningMessage));
                            break;
                    }
                },
                setAttribute: function(data) {
                    var attribute = data.find("attributeId");
                    var fieldName = data.find("attributeId", "fieldName");
                    var newValue = data.find("attributeId", "fieldName", "newValue");
                    switch (attribute) {
                        case 'content':
                            var data = self.getData(fieldName);
                            data.unparsedContent = newValue;
                            if (data.unparsedContent !== "") {
                                if ((! data.modifiedByUser || ! data.value || data.value.length == 0)) {
                                    var content = self.evaluate(data.unparsedContent);
                                    if (content !== false) {
                                        if (content && data.type === "multichoice" && ! $.isArray(content)) {
                                            if (/\[\]$/.test(content)) {
                                                content = JSON.parse(content);
                                            } else {
                                                content = [content];
                                            }
                                        }
                                        if (data.value !== content) {
                                            self.setValue(fieldName, content);
                                        }
                                    }
                                }
                            } else {
                                self.unsetValue(fieldName);
                            }
                            break;
                        case 'default':
                            self.getData(fieldName).unparsedDefault = newValue;
                            break;
                        case 'explanation':
                            self.getData(fieldName).unparsedExplanation = newValue;
                            break;
                        case 'index':
                            self.getData(fieldName).unparsedIndex = newValue;
                            self.reevaluateFields(fieldName);
                            break;
                        case 'min':
                            self.getData(fieldName).unparsedMin = newValue;
                            self.resetMin(fieldName);
                            break;
                        case 'max':
                            self.getData(fieldName).unparsedMax = newValue;
                            self.resetMax(fieldName);
                            break;
                        case 'source':
                            self.getData(fieldName).unparsedSource = newValue;
                            break;
                    }
                },
                unsetAttribute: function(data) {
                    var attribute = data.find("attributeId");
                    var fieldName = data.find("attributeId", "fieldName");
                    switch (attribute) {
                        case 'content':
                            var data = self.getData(fieldName);
                            data.unparsedContent = '';
                            self.unsetValue(fieldName);
                            break;
                        case 'default':
                            self.getData(fieldName).unparsedDefault = '';
                            break;
                        case 'explanation':
                            self.getData(fieldName).unparsedExplanation = '';
                            break;
                        case 'index':
                            self.getData(fieldName).unparsedIndex = '';
                            self.reevaluateFields(fieldName);
                            break;
                        case 'min':
                            self.getData(fieldName).unparsedMin = '';
                            self.resetMin(fieldName);
                            break;
                        case 'max':
                            self.getData(fieldName).unparsedMax = '';
                            self.resetMax(fieldName);
                            break;
                        case 'source':
                            self.getData(fieldName).unparsedSource = '';
                            break;
                    }
                },
                hideObject: function(data) {
                    var currStepId = $('input[name=step]').eq(0).val();
                    var objectId = data.find("objectId");
                    var stepId = data.find("objectId", "stepId");
                    if (stepId == currStepId) {
                        switch (objectId) {
                            case 'step':
                                break;
                            case 'panel':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId));
                                break;
                            case 'fieldset':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId));
                                break;
                            case 'fieldrow':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldrowId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldrowId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId + "-fieldrow-" + fieldrowId));
                                break;
                            case 'field':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "]"));
                                break;
                            case 'blockinfo':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId));
                                break;
                            case 'chapter':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                var chapterId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId + "-chapter-" + chapterId));
                                break;
                            case 'section':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                var chapterId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId");
                                var sectionId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId", "sectionId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId + "-chapter-" + chapterId + "-section-" + sectionId));
                                break;
                            case 'prenote':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "] .pre-note"));
                                break;
                            case 'postnote':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.hideObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "] .post-note"));
                                break;
                            case 'action':
                                var actionId = data.find("objectId", "stepId", "actionId");
                                var action = "#g6k_form button[name=" + actionId + "], #g6k_form input[name=" + actionId + "]";
                                $(action).attr('aria-hidden', true).prop('disabled', true).hide();
                                break;
                            case 'footnote':
                                var footnoteId = data.find("objectId", "stepId", "footnoteId");
                                var footnote = "#foot-note-" + footnoteId;
                                self.hideObject($(footnote));
                                if ( $("div.foot-notes").has("div.foot-note:visible").length) {
                                    self.showObjectLater($("div.foot-notes"));
                                } else {
                                    self.hideObject($("div.foot-notes"));
                                }
                                break;
                            case 'choice':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                var choiceId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId", "choiceId");
                                var field = $("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "]");
                                if (field.attr('data-type') === 'choice' && (!field.attr('data-expanded') || field.attr('data-expanded') === 'false')) {
                                    var input = field.find("input.listbox-input, select");
                                    if (input.is('select')) {
                                        input.hideOption(choiceId);
                                    } else {
                                        input.listbox('hideItem', choiceId);
                                    }
                                } else {
                                    var input = field.find("input[value=" + choiceId + "]");
                                    input.parent('label').attr('aria-hidden', true).hide();
                                }
                                break;
                        }
                    }
                },
                showObject: function(data) {
                    var currStepId = $('input[name=step]').eq(0).val();
                    var objectId = data.find("objectId");
                    var stepId = data.find("objectId", "stepId");
                    if (stepId == currStepId) {
                        switch (objectId) {
                            case 'step':
                                break;
                            case 'panel':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                self.showObjectLater($("#" + self.simu.step.name + "-panel-" + panelId));
                                break;
                            case 'fieldset':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                self.showObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId));
                                break;
                            case 'fieldrow':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldrowId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldrowId");
                                self.showObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId + "-fieldrow-" + fieldrowId));
                                break;
                            case 'field':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.showObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "]"));
                                break;
                            case 'blockinfo':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                self.showObjectLater($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId));
                                break;
                            case 'chapter':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                var chapterId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId");
                                self.showObjectLater($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId + "-chapter-" + chapterId));
                                break;
                            case 'section':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var blockinfoId = data.find("objectId", "stepId", "panelId", "blockinfoId");
                                var chapterId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId");
                                var sectionId = data.find("objectId", "stepId", "panelId", "blockinfoId", "chapterId", "sectionId");
                                self.showObjectLater($("#" + self.simu.step.name + "-panel-" + panelId + "-blockinfo-" + blockinfoId + "-chapter-" + chapterId + "-section-" + sectionId));
                                break;
                            case 'prenote':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.showObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "] .pre-note"));
                                break;
                            case 'postnote':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                self.showObject($("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "] .post-note"));
                                break;
                            case 'action':
                                var actionId = data.find("objectId", "stepId", "actionId");
                                var action = "#g6k_form button[name=" + actionId + "], #g6k_form input[name=" + actionId + "]";
                                $(action).show().removeAttr('aria-hidden').prop('disabled', false);
                                break;
                            case 'footnote':
                                var footnoteId = data.find("objectId", "stepId", "footnoteId");
                                var footnote = "#foot-note-" + footnoteId;
                                $(footnote).show().removeAttr('aria-hidden');
                                self.showObjectLater($("div.foot-notes"));
                                break;
                            case 'choice':
                                var panelId = data.find("objectId", "stepId", "panelId");
                                var fieldsetId = data.find("objectId", "stepId", "panelId", "fieldsetId");
                                var fieldId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId");
                                var choiceId = data.find("objectId", "stepId", "panelId", "fieldsetId", "fieldId", "choiceId");
                                var field = $("#" + self.simu.step.name + "-panel-" + panelId + "-fieldset-" + fieldsetId).find("div[data-field-position=" + fieldId + "]");
                                if (field.attr('data-type') === 'choice' && (!field.attr('data-expanded') || field.attr('data-expanded') === 'false')) {
                                    var input = field.find("input.listbox-input, select");
                                    if (input.is('select')) {
                                        input.showOption(choiceId);
                                    } else {
                                        input.listbox('showItem', choiceId);
                                    }
                                } else {
                                    var input = field.find("input[value=" + choiceId + "]");
                                    input.parent('label').show().removeAttr('aria-hidden');
                                }
                                break;
                        }
                    }
                }
            };
            this.rulesengine = new RuleEngine({
                rulesData: rulesData,
                actionsAdapter: actionsAdapter
            });

            this.rulesengine.runAll(this.variables,
                function(err, result) {
                    if (err) {  }
                }
            );

            $(".simulator-profiles ul li").on("click", function () {
                self.setProfile($(this));
                return true;
            });

            $(".simulator-profiles ul li").on("keydown", function (event) {
                if (event.keyCode == 13 || event.keyCode == 32) {
                    self.setProfile($(this));
                }
                return true;
            });

            $("#g6k_form input[name], #g6k_form select[name], #g6k_form textarea[name]").change(function () {
                clearTimeout(self.inputTimeoutId);
                var name = self.normalizeName($(this).attr('name'));
                self.lastUserInputName = name;
                var data = self.getData(name);
                data.modifiedByUser = true;
                self.removeGlobalError();
                var value = $(this).val();
                if ($(this).attr('type') === 'checkbox') {
                    if (data.type === 'boolean') {
                        value = $(this).is(':checked') ? 'true' : 'false';
                        self.setValue(name, value);
                    } else if (data.type === 'multichoice') {
                        if ($(this).is(':checked')) {
                            self.setValue(name, value);
                        } else {
                            self.unsetChoiceValue(name, value);
                        }
                    }
                } else {
                    self.setValue(name, value);
                }
            });
            $("#g6k_form input[name], #g6k_form select[name], #g6k_form textarea[name]").focusout(function () {
                var name = self.normalizeName($(this).attr('name'));
                var data = self.getData(name);
                if (!self.check(data)) {
                    switch (data.type) {
                        case 'date':
                            self.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans(Date.format) }, 'messages'));
                            break;
                        case 'number':
                            self.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("numbers only") }, 'messages'));
                            break;
                        case 'integer':
                            self.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("numbers only") }, 'messages'));
                            break;
                        case 'money':
                            self.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("amount") }, 'messages'));
                            break;
                        case 'percent':
                            self.setError(name, Translator.trans("This value is not in the expected format (%format%)",  { "format": Translator.trans("percentage") }, 'messages'));
                            break;
                        default:
                            self.setError(name, Translator.trans("This value is not in the expected format"));
                    }
                } else if (!self.checkMin(data)) {
                    var min = self.evaluate(data.unparsedMin);
                    if (data.type == 'text' || data.type == 'textarea') {
                        self.setError(name, Translator.trans("The length of this value can not be less than %min%",  { "min": min }, 'messages'));
                    } else {
                        self.setError(name, Translator.trans("This value can not be less than %min%",  { "min": min }, 'messages'));
                    }
                } else if (!self.checkMax(data)) {
                    var max = self.evaluate(data.unparsedMax);
                    if (data.type == 'text' || data.type == 'textarea') {
                        self.setError(name, Translator.trans("The length of this value can not be greater than %max%",  { "max": max }, 'messages'));
                    } else {
                        self.setError(name, Translator.trans("This value can not be greater than %max%",  { "max": max }, 'messages'));
                    }
                }
            });
            $("#g6k_form input[type=text][name], #g6k_form input[type=money][name], #g6k_form input[type=number][name]").on("keypress", function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    self.getData($(this).attr('name')).modifiedByUser = true;
                    $(this).trigger("change");
                    $(this).focusNextInputField();
                }
            });
            $("#g6k_form input[type=text][name]:not([data-widget]), #g6k_form input[type=money][name]:not([data-widget]), #g6k_form input[type=number][name]:not([data-widget])").on('input propertychange', function(event) {
                var elt = this;
                if (!this.hasAttribute('minlength') || $(this).val().length >= parseInt($(this).attr('minlength'), 10)) {
                    self.triggerChange($(this), true, true);
                }
            });
            $("#g6k_form input[type=text][name], #g6k_form input[type=money][name]").on('paste', function(event) {
                var elt = this;
                self.getData($(this).attr('name')).modifiedByUser = true;
                clearTimeout(self.inputTimeoutId);
                self.inputTimeoutId = setTimeout(function () {
                    $(elt).trigger("change");
                    $(elt).focusNextInputField();
                }, 0);
            });
            $("#g6k_form fieldset label.choice input[type=radio][name]").change(function(event) {
                var $label = $(this).parent('label.choice');
                $label.parent('fieldset').find('label.choice').removeClass('checked');
                if ( $(this).is(':checked') ) {
                    $label.addClass('checked');
                }
            });
            $("#g6k_form fieldset input[type=checkbox][name]").change(function(event) {
                var id = $(this).attr('id');
                var label = $(this).closest('fieldset').find("label[for='" + id + "']");
                if ($(this).is(':checked')) {
                    label.addClass('checked');
                } else {
                    label.removeClass('checked');
                }
            });
            $("#g6k_form fieldset label.choice input[type=radio][name]").focus(function(event) {
                var $label = $(this).parent('label.choice');
                $label.parent('fieldset').addClass('focused');
                var checked = false;
                var $this = $(this);
                $label.parent('fieldset').find('label.choice input[type=radio][name]').each(function() {
                    if ( $(this).is(':checked') ) {
                        checked = true;
                    }
                });
                if (!checked) {
                    $label.eq(0).addClass('checked-candidate');
                }
            });
            $("#g6k_form fieldset label.choice input[type=radio][name]").blur(function(event) {
                var $fieldset = $(this).parent('label.choice').parent('fieldset');
                var focused = false;
                var $this = $(this);
                $fieldset.find('label.choice input[type=radio][name]').each(function() {
                    if ( $(this).is(':focus') ) {
                        focused = true;
                    }
                });
                if (!focused) {
                    $fieldset.removeClass('focused');
                }
                $fieldset.find('label.choice').removeClass('checked-candidate');
            });
            $( "#g6k_form input[type=submit][name], #g6k_form button[type=submit][name]" ).click(function( event ) {
                self.lastSubmitBtn = this.name;
            });
            $( "#g6k_form input[type=submit][name], #g6k_form button[type=submit][name]" ).keypress(function( event ) {
                var key = event.which || event.keyCode;
                if (key == 13) {
                    self.lastSubmitBtn = this.name;
                }
            });
            $( "#g6k_form").submit(function( event ) {
                var bname = self.lastSubmitBtn;
                var bwhat = self.simu.step.actions[bname].what;
                var bfor = self.simu.step.actions[bname].for;
                if (bwhat == 'submit' && bfor == 'priorStep') {
                    return;
                }
                if (bwhat == 'submit' && bfor == 'newSimulation') {
                    $('#g6k_form').clearForm();
                    $("input.resettable").val("");
                    return;
                }
                if (self.hasFatalError || ! self.validateAll()) {
                    self.setGlobalError(Translator.trans("To continue you must first correct your entry"));
                    event.preventDefault();
                }
            });
            $.each(this.simu.datas, function( name, data ) {
                data.value = self.variables[name];
                if (typeof data.unparsedContent !== "undefined" && data.unparsedContent !== "") {
                    var content = self.evaluate(data.unparsedContent);
                    if (content !== false) {
                        if (content && data.type === "multichoice" && ! $.isArray(content)) {
                            if (/\[\]$/.test(content)) {
                                content = JSON.parse(content);
                            } else {
                                content = [content];
                            }
                        } else if (content && (data.type === "money" || data.type === "percent")) {
                            content = self.unFormatValue(content);
                            content = parseFloat(content).toFixed(data.round || 2);
                        } else if (content && data.type === "number") {
                            content = self.unFormatValue(content);
                            if (data.round) {
                                content = parseFloat(content).toFixed(data.round);
                            }
                        }
                        data.value = content;
                        self.setVariable(name, data);
                    } else if (data.value !== '') {
                        data.value = '';
                        self.setVariable(name, data);
                    }

                }
            });
            if ($("input[name='script']").val() == 0) {
                $.each(this.simu.datas, function( name, data ) {
                    self.reevaluateFields(name);
                });
                $("input[name='script']").val(1);
            } else {
                $.each(this.simu.datas, function( name, data ) {
                    self.reevaluateFields(name);
                });
            }
            if ( $("div.foot-notes").children("div.foot-note").filter(":visible").length) {
                self.showObjectLater($("div.foot-notes"));
            } else {
                self.hideObject($("div.foot-notes"));
            }
        },

        triggerChange: function(input, delayed, modifiedByUser) {
            var self = this;
            clearTimeout(self.inputTimeoutId);
            if (typeof modifiedByUser !== "undefined") {
                self.getData(input.attr('name')).modifiedByUser = modifiedByUser;
            }
            if (delayed) {
                self.inputTimeoutId = setTimeout(function () {
                    input.trigger("change");
                }, 500);
            } else {
                input.trigger("change");
            }
        },

        initializeWidgets: function() {
            var self = this;
            var options = {
                locale: self.locale,
                mobile: self.isMobile,
                dateFormat: self.dateFormat,
                decimalPoint: self.decimalPoint,
                moneySymbol: self.moneySymbol,
                symbolPosition: self.symbolPosition,
                groupingSeparator: self.groupingSeparator,
                groupingSize: self.groupingSize
            };
            $(':input[data-widget]').each(function() {
                var widget = window[$(this).attr('data-widget')];
                var that = $(this);
                that.data('g6k', self);
                widget.call(null, that, options, function (value, text, preserveVal, delayed) {
                    if (!preserveVal) {
                        that.val(value);
                    }
                    self.triggerChange(that, delayed);
                });
            });
        },

        initializeExternalFunctions: function() {
            var self = this;
            $('div.action_buttons > [data-function]').each(function() {
                var func = $(this).attr('data-function');
                func = func.replace(/'/g, '"');
                func = $.parseJSON(func);
                var funct = window[func.function];
                var that = $(this);
                that.data('g6k', self);
                funct.call(null, that, func, function(ok, message) {
                    if (self.hasGlobalError) {
                        self.removeGlobalError();
                    }
                    if (message) {
                        if (ok) {
                            var mess = $('<div>', {
                                'class': func.function.toLowerCase() + '-function-status',
                                'aria-live': 'assertive',
                                'html': '<p>' + Translator.trans(message) + '</p>'
                            });
                            that.parent().after(mess);
                            mess.fadeOut(7000, function() {
                                setTimeout(function() {
                                    mess.remove();
                                }, 10);
                            });
                        } else {
                            self.setGlobalError(Translator.trans(message));
                        }
                    }
                });
            });
        },

        hideObject: function(obj) {
            obj.attr('aria-hidden', true).hide();
            return obj;
        },

        showObject: function(obj, delay) {
            obj.show().removeAttr('aria-hidden');
            return obj;
        },

        showObjectLater: function(obj, delay) {
            delay = delay || 120;
            setTimeout(function(){ obj.show().removeAttr('aria-hidden'); }, delay);
            return obj;
        },

        choiceLabel: function(data) {
            var label = '';
            if (data.choices) {
                $.each(data.choices, function(c, choice) {
                    if (choice[data.value]) {
                        label = choice[data.value];
                        return false;
                    }
                });
            }
            return label;
        },

        formatValue: function(data) {
            var value = data.value;
            if (value && $.isNumeric(value) && (data.type === "money" || data.type === "percent")) {
                value = AutoNumeric.format(parseFloat(value), {
                    currencySymbol: '',
                    decimalCharacter: this.decimalPoint,
                    decimalPlaces: data.round || 2,
                    digitGroupSeparator: this.groupingSeparator,
                    digitalGroupSpacing: this.groupingSize
                });
            }
            if (value && data.type === "number") {
                value = AutoNumeric.format(value, {
                    decimalCharacter: this.decimalPoint,
                    decimalPlaces: data.round || null,
                    digitGroupSeparator: this.groupingSeparator,
                    digitalGroupSpacing: this.groupingSize
                });
            }
            if (value && data.type === "text") {
                if (/^https?\:\/\//.test(value)) {
                    if (/(jpg|jpeg|gif|png|svg)$/i.test(value)) {
                        value = '<img src="'+value+'" alt="'+value+'">';
                    } else {
                        value = '<a href="'+value+'">'+value+'</a>';
                    }
                } else if (/^data\:image\//.test(value)) {
                    value = '<img src="'+value+'" alt="*">';
                }
            }
            if ($.isArray(value)) {
                value = value.join(", ");
            }
            return value;
        },

        unFormatValue: function(value) {
            var ts = new RegExp(this.groupingSeparator.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            var dp = new RegExp(this.decimalPoint.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            value = value.replace(ts, '').replace(dp, '.');
            return value;
        },

        replaceVariablesBase: function(target) {
            var self = this;
            var result = target.replace(
                /\<data\s+[^\s]*\s*value="(\d+)"[^\>]*\>[^\<]+\<\/data\>(L?)/g,
                function (match, m1, m2, offs, str) {
                    var name = self.getDataNameById(m1);
                    return (name) ? '#(' + name + ')' + m2 : match;
                }
            );
            result = result.replace(
                /#\(([^\)]+)\)(L?)/g,
                function (match, m1, m2, offs, str) {
                    var data = self.getData(m1);
                    if (data && data.value) {
                        if (m2 === 'L') {
                            var label = self.choiceLabel(data);
                            if (label !== '') {
                                return label;
                            }
                        }
                        return self.formatValue(data);
                    } else {
                        return match;
                    }
                }
            );
            return result;
        },

        replaceVariables: function(target) {
            var result = this.replaceVariablesBase(target);
            return /#\(([^\)]+)\)/.test(result) ? false : result;
        },

        replaceVariablesOrBlank: function(target) {
            var self = this;
            var result = self.replaceVariablesBase(target);
            result = result.replace(
                /#\(([^\)]+)\)(L?)/g,
                function (match, m1, m2, offs, str) {
                    var data = self.getData(m1);
                    switch (data.type) {
                        case 'integer':
                        case 'number':
                            return '0';
                        case 'percent':
                        case 'money':
                            var v = data.value;
                            data.value = '0';
                            var formatted =  self.formatValue(data);
                            data.value = v;
                            return formatted;
                        default:
                            return '';
                    }
                }
            );
            result = result.replace(
                /\<data\s+[^\s]*\s*value="(\d+)"[^\>]*\>[^\<]+\<\/data\>(L?)/g,
                function (match, m1, m2, offs, str) {
                    var data = self.getData(m1);
                    switch (data.type) {
                        case 'integer':
                        case 'number':
                            return '0';
                        case 'percent':
                        case 'money':
                            var v = data.value;
                            data.value = '0';
                            var formatted =  self.formatValue(data);
                            data.value = v;
                            return formatted;
                        default:
                            return '';
                    }
                }
            );
            return result;
        }

    };

    global.G6k = G6k;

}(this));

$.fn.clearForm = function() {
    this.each(function() {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
            return $(':input',this).clearForm();
        if (type == 'text' || type == 'password'  || type == 'number'|| tag == 'textarea') {
            this.setAttribute('value', '');
            if ($(this).hasClass('listbox-input')) {
                $(this).listbox('update');
            }
        } else if (type == 'checkbox' || type == 'radio')
            this.removeAttribute('checked');
        else if (type == 'select-one' || tag == 'select') {
            $('option', this).each(function(){
                this.removeAttribute('selected');
            });
            $(this).val("");
        }
    });

};
