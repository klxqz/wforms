(function($) {
    // js controller
    $.wforms_form = {
        // init js controller
        options: {},
        init: function(options) {
            this.options = options;
            var self = this;

            if ($('#form-description-content').length) {
                $.wforms.initDialogWysiwyg($('#edit-form'), $('#form-description-content'));
            }

            this.initSaveField();
            this.initSubmitForm();
            this.initEditField();
            this.initDeleteField();
            this.initChangeFieldType();
            this.initAddFieldValue();
            this.initDeleteFieldValue();
            this.initSwitchItem();
            this.initSortable();


            $('#content').on('click', '.js-action', function() {
                self.dispatch($(this).attr('href').replace(/^[^#]*#\/*/, ''));
                return false;
            });
        },
        // dispatch call method by hash
        dispatch: function(hash) {
            if (hash) {
                // clear hash
                hash = hash.replace(/^.*#/, '');
                hash = hash.split('/');
                if (hash[0]) {
                    var actionName = "";
                    var attrMarker = hash.length;
                    for (var i = 0; i < hash.length; i++) {
                        var h = hash[i];
                        if (i < 2) {
                            if (i === 0) {
                                actionName = h;
                            } else if (parseInt(h, 10) != h) {
                                actionName += h.substr(0, 1).toUpperCase() + h.substr(1);
                            } else {
                                attrMarker = i;
                                break;
                            }
                        } else {
                            attrMarker = i;
                            break;
                        }
                    }
                    var attr = hash.slice(attrMarker);
                    // call action if it exists
                    if (this[actionName + 'Action']) {
                        this.currentAction = actionName;
                        this.currentActionAttr = attr;
                        this[actionName + 'Action'](attr);
                    } else {
                        if (console) {
                            console.log('Invalid action name:', actionName + 'Action');
                        }
                    }
                }
            }
        },
        initSaveField: function() {
            $('#edit-form').on('click', '.save-field-but', function() {
                var field_row = $(this).closest('.field-row');

                $.ajax({
                    type: 'POST',
                    url: '?action=saveField',
                    dataType: 'json',
                    data: field_row.find(':input').serialize(),
                    success: function(data, textStatus, jqXHR) {
                        if (data.status == 'ok') {
                            var tpl = $('#field-tmpl').tmpl(data.data.field);
                            field_row.replaceWith(tpl);
                            //$('.add-edit-field').remove();
                        } else {
                            alert(data.errors.join(' '));
                        }
                    }
                });
                return false;
            });
        },
        addFieldAction: function() {
            var data = {
                default_value: '',
                type: 'text',
                form_id: this.options.form_id,
                field_types: this.options.field_types,
                required: 1
            };
            $('#add-edit-field-tmpl').tmpl(data).appendTo('#form-fiels-table');
        },
        initAddFieldValue: function() {
            $('#edit-form').on('click', '.add-field-value-but', function() {
                var type = $(this).closest('.field-row').find('.field-type').val();
                var tpl;

                switch (type) {
                    case 'checkboxes':
                        tpl = $('#type-checkboxes-field-tmpl').tmpl();
                        break;
                    case 'radio':
                        tpl = $('#type-radio-field-tmpl').tmpl();
                        break;
                    case 'select':
                        tpl = $('#type-radio-field-tmpl').tmpl();
                        break;
                    case 'select_multiple':
                        tpl = $('#type-checkboxes-field-tmpl').tmpl();
                        break;
                }
                $(this).closest('.field-values').find('ul').append(tpl);



                return false;
            });
        },
        initSwitchItem: function() {
            $('#edit-form').on('change', '.field-values li input[type=checkbox].switch-item', function() {
                if ($(this).prop('checked')) {
                    $(this).closest('li').find('input[type=hidden].switch-item').attr('disabled', true);
                } else {
                    $(this).closest('li').find('input[type=hidden].switch-item').removeAttr('disabled');
                }
            });
            $('#edit-form').on('change', '.field-values li input[type=radio].switch-item', function() {
                if ($(this).prop('checked')) {
                    $(this).closest('.field-values').find('input[type=hidden].switch-item').removeAttr('disabled');
                    $(this).closest('li').find('input[type=hidden].switch-item').attr('disabled', true);
                }
            });
        },
        initDeleteFieldValue: function() {
            $('#edit-form').on('click', '.delete-field-value-but', function() {
                $(this).closest('li').remove();
                return false;
            });
        },
        initEditField: function() {
            $('#edit-form').on('click', '.edit-but', function() {
                var field_row = $(this).closest('.field-row');
                var field_id = field_row.data('id');
                $.ajax({
                    type: 'POST',
                    url: '?action=editField',
                    dataType: 'json',
                    data: {id: field_id},
                    success: function(data, textStatus, jqXHR) {
                        if (data.status == 'ok') {
                            var tpl = $('#add-edit-field-tmpl').tmpl(data.data.field);
                            field_row.replaceWith(tpl);
                            tpl.find('select.field-type').change();
                            tpl.find(".field-values ul").sortable();

                        } else {
                            alert(data.errors.join(' '));
                        }
                    }
                });
                return false;
            });
        },
        initSubmitForm: function() {
            $('#edit-form').submit(function() {
                var form = $(this);
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    dataType: 'json',
                    data: form.serialize(),
                    success: function(data, textStatus, jqXHR) {
                        if (data.status == 'ok') {
                            form.find('.response').html('<span class="success-msg">' + data.data.message + '</span>');
                        } else {
                            form.find('.response').html('<span class="error-msg">' + data.errors.join() + '</span>');
                        }
                        setTimeout(function() {
                            form.find('.response span').hide();
                        }, 3000);
                    }
                });
                return false;
            });
        },
        initDeleteField: function() {
            $('#edit-form').on('click', '.delete-but', function() {
                var field_row = $(this).closest('.field-row');
                var field_id = field_row.data('id');
                $.ajax({
                    type: 'POST',
                    url: '?action=deleteField',
                    dataType: 'json',
                    data: {id: field_id},
                    success: function(data, textStatus, jqXHR) {
                        if (data.status == 'ok') {
                            field_row.remove();
                        } else {
                            alert(data.errors.join(' '));
                        }
                    }
                });
                return false;
            });
        },
        initChangeFieldType: function() {
            $('#edit-form').on('change', '.add-edit-field select.field-type', function() {
                if ($(this).val() == 'select_multiple' || $(this).val() == 'select' || $(this).val() == 'radio' || $(this).val() == 'checkboxes') {
                    var field_values = $(this).closest('.add-edit-field').find('.field-values');
                    field_values.show();
                    if ($(this).val() == 'radio' || $(this).val() == 'select') {
                        field_values.find('input.switch-item[type=checkbox]').each(function() {
                            var name = $(this).attr('name');
                            $(this).replaceWith('<input class="switch-item" type="radio" name="' + name + '" value="1"/>');
                        });
                        field_values.find('input.switch-item[type=radio]:first').change();
                        //field_values.find('input.switch-item[type=checkbox]').attr('type', 'radio');
                    } else {
                        field_values.find('input.switch-item[type=radio]').each(function() {
                            var name = $(this).attr('name');
                            $(this).replaceWith('<input class="switch-item" type="checkbox" name="' + name + '" value="1"/>');
                        });
                        field_values.find('input.switch-item[type=checkbox]:first').change();
                        //field_values.find('input.switch-item[type=radio]').replaceWith();
                    }

                } else {
                    $(this).closest('.add-edit-field').find('.field-values').hide();
                }
            });
        },
        initSortable: function() {
            var self = this;
            $("#form-fiels-table").sortable({
                'handle': '.tr-sortable-handle',
                'items': '> tbody:first > tr.field-row',
                'update': function(event, ui) {
                    var $field = $(ui.item);
                    var $after = $field.prev(':visible');
                    self.fieldSort($field, $after, $(this));
                },
            });
        },
        fieldSort: function($field, $after, $fields) {
            var id = parseInt($field.data('id'));
            var after_id = $after.data('id');
            if (after_id === undefined) {
                after_id = 0;
            } else {
                after_id = parseInt(after_id);
            }

            //var type = this.featuresHelper.type();

            $.post('?action=fieldSort', {
                field_id: id,
                after_id: after_id
            }, function(data, textStatus) {
                if (data.status == 'ok') {

                } else {
                    $fields.sortable('cancel');
                }
            }, 'json').error(function(jqXHR, errorText) {
                $fields.sortable('cancel');
            });
        }
    }
})(jQuery);