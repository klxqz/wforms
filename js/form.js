(function($) {
    // js controller
    $.wforms = {
        // init js controller
        init: function() {
            var self = this;
            // if history exists
            if (typeof ($.History) != "undefined") {
                $.History.bind(function(hash) {
                    $.wforms.dispatch(hash);
                });
            }

            $('#form-add-link').click(function() {
                $.wforms.addFormDialog();
                return false;
            });

            $('#content').on('click', '.js-action', function() {
                self.dispatch($(this).attr('href').replace(/^[^#]*#\/*/, ''));
                return false;
            });

            this.dispatch();
        },
        // dispatch call method by hash
        dispatch: function(hash) {
            if (hash === undefined) {
                hash = location.hash.replace(/^[^#]*#\/*/, '');
            }
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
                } else {
                    // call default action
                    this.defaultAction();
                }
            } else {
                // call default action
                this.defaultAction();
            }
        },
        defaultAction: function() {
            $("#content").load('?action=records');
        },
        formAction: function(params) {
            $("#content").load('?action=form&id=' + params, function() {
                if ($('#messagebox-description-content').length) {
                    $.wforms.initDialogWysiwyg($(this));
                }
                $.wforms.formInit();
            });
        },
        saveFieldAction: function() {
            var form = $('#edit-form');
            $.ajax({
                type: 'POST',
                url: '?action=saveField',
                dataType: 'json',
                data: form.serialize(),
                success: function(data, textStatus, jqXHR) {
                    if (data.status == 'ok') {

                        $('#field-tmpl').tmpl(data.data.field).appendTo('#form-fiels-table');
                        $('.add-edit-field').remove();
                        //form.find('.dialog-response').html('<span class="success-msg">' + data.data.message + '</span>');
                    } else {
                        alert(data.errors.join(' '));
                    }
                }
            });

        },
        formInit: function() {
            $('#add-field-but').click(function() {
                var data = {
                    field_types: $field_types,
                    required: 1
                };
                $('#add-edit-field-tmpl').tmpl(data).appendTo('#form-fiels-table');
                return false;
            });
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
                            field_row.replaceWith($('#add-edit-field-tmpl').tmpl(data.data.field));
                            //$('#field-tmpl').tmpl(data.data.field).('#form-fiels-table .field-add-row');
                            //field_row.remove();
                        } else {
                            alert(data.errors.join(' '));
                        }
                    }
                });
                return false;
            });
        },
        addFormDialog: function(messagebox_id) {
            messagebox_id = messagebox_id || null;
            var showDialog = function() {
                $('#add-form-dialog').waDialog({
                    disableButtonsOnSubmit: true,
                    onLoad: function() {
                        if ($('#messagebox-description-content').length) {
                            $.wforms.initDialogWysiwyg($(this));
                        }
                    },
                    onSubmit: function(d) {
                        var form = $(this);
                        if ($('#messagebox-description-content').length) {
                            $('#messagebox-description-content').waEditor('sync');
                        }
                        $.ajax({
                            type: 'POST',
                            url: form.attr('action'),
                            dataType: 'json',
                            data: form.serialize(),
                            success: function(data, textStatus, jqXHR) {
                                if (data.status == 'ok') {
                                    form.find('.dialog-response').html('<span class="success-msg">' + data.data.message + '</span>');
                                } else {
                                    form.find('.dialog-response').html('<span class="error-msg">' + data.errors.join() + '</span>');
                                }
                            }
                        });
                        return false;
                    }
                });
            };
            var d = $('#add-form-dialog');
            var p;
            if (!d.length) {
                p = $('<div></div>').appendTo('body');
            } else {
                p = d.parent();
            }
            if (messagebox_id) {
                p.load('?action=dialog&messagebox_id=' + messagebox_id, showDialog);
            } else {
                p.load('?action=dialog', showDialog);
            }


        },
        initDialogWysiwyg: function(d) {
            var field = d.find('.field.description');
            field.find('i').hide();
            field.find('.s-editor-core-wrapper').show();
            var height = (d.find('.dialog-window').height() * 0.8) || 350;

            $('#messagebox-description-content').waEditor({
                lang: wa_lang,
                toolbarFixedBox: false,
                maxHeight: height,
                minHeight: height,
                uploadFields: d.data('uploadFields')
            });
        }
    }
})(jQuery);