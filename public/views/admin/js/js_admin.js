$(document).ready(function () {
/* *********** GLOBAL VAR ******************* */
    var activeTextArea = null;
    var imageUrlBase = '';
    var imageTitle = '';
    var imageAlt = '';
    var name = '';
    var imgflag = false;
    var imgThrumb_bool = 0;
    var src = '';
    /* ****** костыль для инпута бутстрап****** */
    $("#add_name").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image_thrumb').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    /* **************   tinyMCE Init   ************* */
    var activeTextArea = null;
    tinymce.init({
        selector: "#mytextarea",
        // подключаем плагины, это подкаталоги в каталоге plugins
        images_reuse_filename: true,
        relative_urls: false,
        remove_script_host: false,
        plugins: [
            'autoresize advlist autolink link image lists codesample charmap print preview hr anchor pagebreak spellchecker code',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table emoticons template paste help'
        ],
        toolbar: 'undo redo | code | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
          ' bullist numlist outdent indent | link image | codesample | print preview media fullpage | ' +
          'forecolor backcolor emoticons | libImage|  help',
          menu: {
            favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
          },
          menubar: 'favs file edit view insert format tools table help',

        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        setup: function (editor) {
        /* Helper functions чтобы что то там сохранялось. Забыл */
        editor.on('change', function () {
            tinymce.triggerSave();
        });
        /* Вставка рисунков в редактор */
        $("#insert_image").click(function () {
            var select_size = $('#image_size').val();
            imageTitle = $('#info_title').val();
            imageAlt = $('#info_alt').val();
            if(imgThrumb_bool == 0){
            switch (select_size) {
            case '1':
                activeTextArea.insertContent('&nbsp;<img src = "' + src + '" alt = "' + imageAlt + '"/> &nbsp;');
                break;
            case '2':
                activeTextArea.insertContent('&nbsp;<img src="' + imageUrlBase + '/thrumb_m/' + name + '"title = "' + imageTitle + '" alt = "' + imageAlt + '"/> &nbsp;');
                break;
            case '3':
                activeTextArea.insertContent('&nbsp;<img src="' + imageUrlBase + '/thrumb_s/' + name + '"title = "' + imageTitle + '" alt = "' + imageAlt + '"/> &nbsp;');
                break;
            }
        }else{
            activeTextArea.insertContent('&nbsp;<img src = "' + src + '" alt = "' + imageAlt + '"/> &nbsp;');
        }
            $('#img_insert').modal('hide');
        });
        /* ********** Basic button вставки рисунка **************** */
        editor.ui.registry.addButton('libImage', {
            text: 'Image from lib',
            tooltip: 'Insert image from library',
            onAction: function (_) {
            activeTextArea = editor; // onclick of this button, set the active textarea to that of which the button was pressed
            $('#img_insert').modal('show');
            $('#image_size').prop('disabled', false);
            imgflag = true;
            $('#insert_image').prop('disabled', false);
            }
        });
        }
    });
    /* ************END tinyMCE ****************/
    /* ***  Настройки для перетаскиваемого списка меню ****** */
    var options = {
        placeholderCss: {'background-color': '#ff8'},
        hintCss: {'background-color':'#bbf'},
        onChange: function( cEl )
        { console.log( 'onChange' ); },
        complete: function( cEl )
        { console.log( 'complete' ); },
        isAllowed: function( cEl, hint, target )
        {if( target.data('module') === 'c' && cEl.data('module') !== 'c' )
            {hint.css('background-color', '#ff9999');
                return false;
            }else{
                hint.css('background-color', '#99ff99');
                return true;
            }
        },
        opener: {
            active: true,
            as: 'html',  // if as is not set plugin uses background image
            close: '<i class="fa fa-minus c3"></i>',  // or 'fa-minus c3',  // or './imgs/Remove2.png',
            open: '<i class="fa fa-plus"></i>',  // or 'fa-plus',  // or'./imgs/Add2.png',
            openerCss: {
                'display': 'inline-block',
                'float': 'left',
                'margin-left': '-35px',
                'margin-right': '5px',
                'font-size': '1.1em'
            }
        },
        ignoreClass: 'clickable'
    };
    /* ********  Перенос выделенных пунктов меню заново ********* */
    $("#move_list").on("click", function(e) {
        var menuHtml = '<ul  class="sTreeList listsClass" id="sTreeList">'
        $("#menu_create input:not(.item_create):checkbox:checked").each(function(){
        var id = $(this).data('uid');
        var  text  =   $(this).next().text().trim();
        menuHtml += '<li id="list_' + id + '" data-value="'+ text +'"><div>' + text +' </div>';
        });
        menuHtml += '</ul>';
        $("#menu_out").html(menuHtml);
        $('#sTreeList').sortableLists( options );
    });
    /* ******* Добавление пункта меню к существующему списку ******** */
    $("#add_list").on("click", function(e) {
        var menuHtml = '';
        $("#menu_create input:checkbox:checked").each(function(){
        var id = $(this).data('uid');
        var  text  =   $(this).next().text().trim();
        menuHtml += '<li id="list_' + id + '" data-value="'+ text +'"><div>' + text +' </div>';
        });
        $("#sTreeList").append(menuHtml);
    });
    /* ********      Clear List Page      ********* */
    $("#clear_list").on("click", function(e) {
        $("#menu_create input:checkbox:checked").each(function(){
        $(this).prop('checked', false);
        });
    });
    /* *************    OPEN SAVE CREATE CRUD MENU *****************/
    /* ******** Установка активным последнего из списка комбобокса   *******/
    $('#bd_menu_name :last').attr("selected", "selected");
    $( "#bd_menu_name" ).change(function() {
        var check_type =  $(this).children("option:selected").data('type');
        if(check_type == 1){
        $('#inlineRadio1').prop("checked", true);
        }else{
        $('#inlineRadio2').prop("checked", true);
        }
    });
    /* ******** открытие меню из списка   **********/
    $("#btn_menu_open").on("click", function(e) {
        $("#switch_route").val('1');
        $("#menuConstruct").submit();
    });
    /* ******** создание имени нового меню   **********/
    $("#btn_menu_new").on("click", function(e) {
        $("#switch_route").val('2');
        $("#menuConstruct").submit();
        $("#new_menu_modal").modal('hide');
    });
    /* ******** сохранение меню под выбранным именем   **********/
    $("#btn_menu_save").on("click", function(e) {
        $("#switch_route").val('3');
        $("#menuConstruct").submit();
    });
    /* ******** создание файла меню из открытого меню   **********/
    $("#btn_menu_create").on("click", function(e) {
        $("#switch_route").val('4');
        $("#menuConstruct").submit();
    });
    /* ******** переопределение работы формы для меню   **********/
    $("#menuConstruct").on("submit", function(e) {
        e.preventDefault();
        ajaxOpenSaveMenu($(this));
    });
    // function animateProgressBar
    function animateProgressBar(text){
        var timerId, percent;
        percent = 0;
        $('#progress').css('width', '0px').addClass('progress-bar-animated active');
        $('#progress').html('');
        $('#progress').fadeIn();
        timerId = setInterval(function() {
            percent += 10;
            $('#progress').css('width', percent + '%');
                if (percent >= 110) {
                clearInterval(timerId);
            $('#progress').removeClass('progress-bar-animated active').html(text);
            $('#progress').fadeOut(5000);
            }
        }, 200);
    }
    // function  ajaxOpenSaveMenu
    function  ajaxOpenSaveMenu(form){
        var id_menu_mame = $('#bd_menu_name').val();
        var menu_mame = $('#bd_menu_name option:selected').text();
        var switch_route = $('#switch_route').val();
        var  menu_name_new = $('#menu_name_new').val();
        var  menu_type = $('#menu_type option:selected').val();
        var  menu_blog = $('#menu_blog:checked').val();
        var  menu_contact = $('#menu_contact:checked').val();
        var array_menu =  $('#sTreeList').sortableListsToArray();
        var menu_data  =  JSON.stringify(array_menu);
        var form_data_temp = {
            "id_menu_mame": id_menu_mame,
            "menu_mame":menu_mame,
            "switch_route":switch_route,
            "menu_name_new":menu_name_new,
            "menu_type":menu_type,
            "menu_blog":menu_blog,
            "menu_contact":menu_contact
        }
        var form_data = JSON.stringify(form_data_temp);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'JSON',
            data: {'form_data': form_data, 'menu_data': menu_data }
        })
        .done(function(data) {
            switch(data.flag) {
            case '1': //// if (open меню')
            $('#menu_out').html(data.menu);
            $('#sTreeList').sortableLists( options );
            var label = 'Открыто меню ' + ' ' + data.menu_name;
            animateProgressBar(label);
            break;
            case '2':
                var label = data.message;
                animateProgressBar(label);
            break;
            case '3':
                var label = data.message;
                animateProgressBar(label);
                break;
            case '4':
                var label =  data.message;
                animateProgressBar(label);
            break;
            }
        })
        .fail(function() {
            alert('Действие не удалось. Попробуйте снова.');
        });
    }
    //  *********  MEDIA LIBRARY*********
    /* ******   Очистка поля миниатюры при переключении вкладок TAB ******* */
   /*  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('#image_thrumb').attr('src', "");
        $('#admin_sidebar .img_name').html('');
        $('#admin_sidebar .img_width').html('');
        $('#admin_sidebar .img_height').html('');
        $('#info_title').val('');
        $('#info_alt').val('');
        $('#img_name_hide').attr('value', '');
    }); */
    /* ******   Информация о рисунке MEDIALIB and modal form medialib ************ */
    $("#media_lib img").on("click", function (e) {
        src = e.target.src;
        imageUrlBase = src.replace(/\/\w+.\w+$/gu, '');
        var id = $(this).data('id');
        imgThrumb_bool = $(this).data('thrumb');
        name = src.split("\/").pop();
        var width = e.target.naturalWidth;
        var height = e.target.naturalHeight;
        var title = e.target.title;
        var alt = e.target.alt;
        $('.img_name').html(name);
        $('.img_width').html(width);
        $('.img_height').html(height);
        $('#info_title').val(title);
        $('#info_alt').val(alt);
        $('#img_name').attr('value', name);
		$('#img_id').attr('value', id);
        $('#img_name').attr('value', name);
        $('#image_thrumb').attr('src', src);
        if (!imgflag) {
            $('#content_thrumb').attr('src', src);
            $('#thrumb').val(name);
    }
    });
     /* *******  Добавить миниатюру ******************* */
     $("#add_thrumb").on("click", function (e) {
        $('#img_insert').modal('show');
        $('#insert_thrumb').prop('disabled', false);
        $('#image_size option[value=3]').prop('selected', true);
        $('#image_size').prop('disabled', true);
    });
    /* *******************  Вставить  миниатюру ********** */
    $("#insert_thrumb").on("click", function (e) {
        $('#img_insert').modal('hide');
    });
    /* ** clear modal window button disabled #insert_image insert_thrumb  *** */
    $('#img_insert').on('hidden.bs.modal', function (e) {
        $('#insert_image').prop('disabled', true);
        $('#insert_thrumb').prop('disabled', true);
        imgflag = false;
    })
    /* ************************  Edit CREATE CONTENT SITE *************** */
    $("#edit_post, #create_post, #create_page, #edit_page ").on( 'submit', function (event) {
        event.preventDefault();
        var $form = $(this);
        url = $form.attr("action");
        $sds = $form.serialize();
		$.post(url, $form.serialize())
            .done(function (data) {
                var obj = jQuery.parseJSON(data);
                var err = "";
                $.each(obj, function (k, v) {
                    $.each(v, function (k1, v1) {
                        err += "------------------------- \n"
                        $.each(v1, function (k2, v2) {
                            err += k2 + ' : ' + v2 + "\n";
                        });
                    });
                });
                var regexp = /OK/g;
                var bool =  regexp.test(err);
                alert(err);
                if(bool){
                location.reload(true);
                }
            }, "json")
            .fail(function (jqXHR, textStatus) {
                alert("Request failed: " + textStatus);
            });
    });
}); /* END   JQUERY LOAD PAGE END FUNCTION*/
/* ****** SLUG Транслитерация    кириллицы в URL ************ */
function slugGenerate() {
    var cat_slug = $('.slug').val();
    if (cat_slug != '') {
        cat_slug = cat_slug.trim();
        cat_slug = cat_slug.replace(/[^a-zA-Zа-яА-Я0-9 ]/g, '');
        cat_slug = cat_slug.replace(/\s+/g, '-');
        cat_slug = cat_slug.replace(/^[\s-]+/g, '');
        cat_slug = cat_slug.replace(/[\s-]+$/g, '');
        var catslug_en = urlRusLat(cat_slug);
    }
    $('#slug').val(catslug_en);
}
function urlRusLat(str) {
    str = str.toLowerCase(); // все в нижний регистр
    var cyr2latChars = new Array(
        ['а', 'a'], ['б', 'b'], ['в', 'v'], ['г', 'g'],
        ['д', 'd'], ['е', 'e'], ['ё', 'yo'], ['ж', 'zh'], ['з', 'z'],
        ['и', 'i'], ['й', 'y'], ['к', 'k'], ['л', 'l'],
        ['м', 'm'], ['н', 'n'], ['о', 'o'], ['п', 'p'], ['р', 'r'],
        ['с', 's'], ['т', 't'], ['у', 'u'], ['ф', 'f'],
        ['х', 'h'], ['ц', 'c'], ['ч', 'ch'], ['ш', 'sh'], ['щ', 'shch'],
        ['ъ', ''], ['ы', 'y'], ['ь', ''], ['э', 'e'], ['ю', 'yu'], ['я', 'ya'],

        ['А', 'A'], ['Б', 'B'], ['В', 'V'], ['Г', 'G'],
        ['Д', 'D'], ['Е', 'E'], ['Ё', 'YO'], ['Ж', 'ZH'], ['З', 'Z'],
        ['И', 'I'], ['Й', 'Y'], ['К', 'K'], ['Л', 'L'],
        ['М', 'M'], ['Н', 'N'], ['О', 'O'], ['П', 'P'], ['Р', 'R'],
        ['С', 'S'], ['Т', 'T'], ['У', 'U'], ['Ф', 'F'],
        ['Х', 'H'], ['Ц', 'C'], ['Ч', 'CH'], ['Ш', 'SH'], ['Щ', 'SHCH'],
        ['Ъ', ''], ['Ы', 'Y'],
        ['Ь', ''],
        ['Э', 'E'],
        ['Ю', 'YU'],
        ['Я', 'YA'],
        ['a', 'a'], ['b', 'b'], ['c', 'c'], ['d', 'd'], ['e', 'e'],
        ['f', 'f'], ['g', 'g'], ['h', 'h'], ['i', 'i'], ['j', 'j'],
        ['k', 'k'], ['l', 'l'], ['m', 'm'], ['n', 'n'], ['o', 'o'],
        ['p', 'p'], ['q', 'q'], ['r', 'r'], ['s', 's'], ['t', 't'],
        ['u', 'u'], ['v', 'v'], ['w', 'w'], ['x', 'x'], ['y', 'y'],
        ['z', 'z'],
        ['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D'], ['E', 'E'],
        ['F', 'F'], ['G', 'G'], ['H', 'H'], ['I', 'I'], ['J', 'J'], ['K', 'K'],
        ['L', 'L'], ['M', 'M'], ['N', 'N'], ['O', 'O'], ['P', 'P'],
        ['Q', 'Q'], ['R', 'R'], ['S', 'S'], ['T', 'T'], ['U', 'U'], ['V', 'V'],
        ['W', 'W'], ['X', 'X'], ['Y', 'Y'], ['Z', 'Z'],
        [' ', '_'], ['0', '0'], ['1', '1'], ['2', '2'], ['3', '3'],
        ['4', '4'], ['5', '5'], ['6', '6'], ['7', '7'], ['8', '8'], ['9', '9'],
        ['-', '-']
    );
    var newStr = new String();
    for (var i = 0; i < str.length; i++) {
        ch = str.charAt(i);
        var newCh = '';
        for (var j = 0; j < cyr2latChars.length; j++) {
            if (ch == cyr2latChars[j][0]) {
                newCh = cyr2latChars[j][1];
            }
        }
        // Если найдено совпадение, то добавляется соответствие, если нет - пустая строка
        newStr += newCh;
    }
    // Удаляем повторяющие знаки - Именно на них заменяются пробелы.
    // Так же удаляем символы перевода строки, но это наверное уже лишнее
    return newStr.replace(/[_]{2,}/gim, '_').replace(/\n/gim, '');
}
