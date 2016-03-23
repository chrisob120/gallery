/**
 * Created by Chris on 1/16/2015.
 */

// enable twitter bootstrap tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// fancybox settings
$(function() {
    $('.fancybox').fancybox({
        'width'				: 500,
        'height'			: 'auto',
        'autoSize'			: false,
        'href'              : '#uploadBox',
        'helpers'			: {
            'overlay': {
                closeClick: true,
                locked: false
            }
        }
    });

    $('.fancylogin').fancybox({
        'width'				: 600,
        'height'			: 400,
        'autoSize'			: false,
        'href'              : '#loginBox',
        'afterShow'         : function () {
            $('[name="username"]').focus();
        },
        'beforeShow'         : function () {
            cleanForm('login');
        },
        'afterClose'		: function() {
            cleanForm('login');
        },
        'helpers'			: {
            'overlay': {
                closeClick: true,
                locked: false
            }
        }
    });

    $('.fancyregister').fancybox({
        'width'				: 600,
        'height'			: 400,
        'autoSize'			: false,
        'href'              : '#registerBox',
        'afterShow'         : function () {
            $('[name="username"]').focus();
        },
        'beforeShow'		: function() {
            cleanForm('register');
        },
        'afterClose'		: function() {
            cleanForm('register');
        },
        'helpers'			: {
            'overlay': {
                closeClick: true,
                locked: false
            }
        }
    });
});

// upload form
$(function(){

    var ul = $('#upload ul');
    var imgCnt = 0;
    var imgNames = [];

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            // make sure the image hasn't already been uploaded in this instance
            if ($.inArray(data.files[0].name, imgNames) == -1) {
                imgNames.push(data.files[0].name); // push the image name to the image name array
                imgCnt += 1; // add to image count

                // update image count
                updateImgCount(imgCnt);

                var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span class="remove" title="Remove ' + data.files[0].name + '"></span></li>');

                // Append the file name and file size
                tpl.find('p').html('<span class="name">' + data.files[0].name + '</span>')
                    .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

                // Add the HTML to the UL element
                data.context = tpl.prependTo(ul);

                // show options
                $('#options').show();

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span.remove').click(function(){
                    var result = jqXHR.responseJSON;

                    // remove temp images
                    $.post(base_url + '/images/removeTmpImages', {images: data.files[0].name}, function() {});

                    // if there was a server side error, the image count has already been decreased
                    if (!result.error) imgCnt -= 1;

                    if (tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();

                        // hide options panel if no images
                        if (imgCnt < 1) $('#options').hide();

                        // update image count
                        updateImgCount(imgCnt);

                        // remove item from image name array. note: this method only works if there are no duplicates in the array
                        imgNames.splice($.inArray(data.files[0].name, imgNames), 1);
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            }
        },

        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,

        progress: function(e, data){
            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if (progress == 100){
                data.context.removeClass('working');
            }
        },

        fail: function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
            imgCnt -= 1;
            updateImgCount(imgCnt);
        },

        done: function(e, data) {
            var result = data.result;

            // check if there were any errors on the server side
            if (!result.error) {
                var divEl = data.context.find('div');
                var f = data.files[0].name;
                var fileName = f.substr(0, f.lastIndexOf('.'));
                fileName = fileName.split(' ').join('_');
                var fileExt = f.substr(f.lastIndexOf('.') + 1);

                // display thumbnail
                $(divEl).replaceWith('<div class="img-h"><img src="' + base_url + '/uploads/tmp/' + fileName + '-thumbnail(_x50).' + fileExt + '" alt="" /></div>');
            } else {
                // remove image from image count and update html
                imgCnt -= 1;
                updateImgCount(imgCnt);

                // add error color if server side error
                data.context.addClass('error');
                //data.context.find('span.name').append('? mark thing');
            }
        }

    });

    // upload the images
    $('#options a.upload').click(function(e) {
        var ul = $(this).parent()[0];
        ul = $(ul).prev();

        // prevent post if there are no images to upload
        if (imgCnt > 0) {
            $.post(base_url + '/images/upload', {files: imgNames}, function (data) {
                var uploadForm = $('#upload');
                // empty the form and the ul
                $(ul).empty();
                $(uploadForm).empty();

                // add header & separator
                $(uploadForm).append('<div id="d-title">Uploading Images...</div><div class="separator"></div>');

                // show each image 'being uploaded'
                $.each(data.images, function(index, val) {
                    $(uploadForm).append('<div class="img-row"><img src="' + val.imgPath + '" alt="" /></div>');
                });

                // add footer
                $(uploadForm).append(ul);
            });
        }
    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

    function updateImgCount(imgCnt) {
        $('.img-count').html(imgCnt);
    }

});

function cleanForm(formChoice) {
    var form = (formChoice == 'login') ? $('#login-hold form') : $('#register-hold form');
    var formEle = form.selector;

    // reset form
    $(form).trigger('reset');

    // remove all classes
    $(formEle + ' input').removeClass('e-field e-span');
    $(formEle + ' p').attr('data-original-title', '');
    $('.error-msg').remove();
}

$(function() {
    var loginForm = $('#login-hold form');
    var registerForm = $('#register-hold form');

    // login form submitted
    $(loginForm).submit(function(e) {
        var values = $(loginForm).serialize();

        $.post(base_url + '/login', values, function (data) {
            if (data.error) {
                var curField, parent, curSpan;

                $.each(data.fields, function(index, field) {
                    curField = $('[name="' + field + '"]');
                    parent = $(curField).parent();
                    curSpan = $(parent).find('span');

                    // if current field is the error field, add errors
                    if (data.field == field) {
                        $(curField).addClass('e-field');
                        $(curSpan).addClass('e-span');
                        $(parent).attr('data-original-title', data.msg);
                    } else {
                        $(curField).removeClass('e-field');
                        $(curSpan).removeClass('e-span');
                        $(parent).attr('data-original-title', '');
                    }
                });
            } else {
                window.location.href = data.returnUrl;
            }
        });

        e.preventDefault();
    });

    // register
    $(registerForm).submit(function(e) {
        var values = $(registerForm).serialize();

        $.post(base_url + '/register', values, function (data) {
            if (data.error) {
                var errorsFieldArr = [];
                var curField, parent, curSpan, msg;

                // Append JSON error array keys to a new array object
                $.each(data.errors, function(field, message) {
                    errorsFieldArr.push(field);
                });

                $.each(data.fields, function(index, field) {
                    curField = $('[name="' + field + '"]');
                    parent = $(curField).parent();
                    curSpan = $(parent).find('span');

                    // If the field name is in the errors field array, update css
                    if ($.inArray(field, errorsFieldArr) !== -1) {
                        // grabs first element from errors array for this input
                        msg = data.errors[field][0];

                        $(curField).addClass('e-field');
                        $(curSpan).addClass('e-span');
                        $(parent).attr('data-original-title', msg);
                    } else {
                        $(curField).removeClass('e-field');
                        $(curSpan).removeClass('e-span');
                        $(parent).attr('data-original-title', '');
                    }
                });
            } else {
                //window.location.href = data.returnUrl;
            }
        });

        e.preventDefault();
    });
});

// user image change
$(function() {
    // attach the span button to the hidden input form
    $('#img-change').click(function() {
        // THIS IS WHAT IS PREVENTING USER IMAGE UPLOAD FROM WORKING FOR THE TIME BEING
        // enable this when interactive cropping works

        //$('#usrImgField').click();
    });

    // submit the new user image form
    $('#usrImgField').change(function() {
        $(this).parent().submit();
    });
});

// disappear logic
$(function() {
    if ($('.disappear').is(":visible")) {
        // delay one second for proper visual
        setTimeout(function(){
            countdown();
        },1000)
    }
});

function countdown() {
    var timer;
    var disappearField = $('.disappear');
    var counterField = $('.counter');
    var seconds = $(counterField).html();
    seconds = parseInt(seconds);

    // if it's on the last second, fade the div
    if (seconds == 1) $(disappearField).fadeOut('slow');

    seconds--;
    $(counterField).html(seconds);
    timer = setTimeout(countdown, 1000);

    // put this below the second counter to stop the interval with one second left
    if (seconds == 0) clearInterval(timer);
}