(function ($) {
    'use strict';

    $(document).ready(function () {

        var cbxteamsocialnetworks = cbxteamadmin.socialnetworks;

        var item_count = $('#cbxteam-custom-repeatable > li').length;


        $('#cbxrmmetabox_team_wrapper').on("change", ".cbxsocial-repeatable-add", function () {

            var type_option = $('.cbxsocial-repeatable-add').val();
            if (type_option == '') return;

            var item = $.parseJSON(cbxteamadmin.socialitemtemplate);

            var type = type_option;

            //console.log(item_count);

            var icon  = cbxteamsocialnetworks[type]['icon'];
            var url   = cbxteamsocialnetworks[type]['url'];
            var title = cbxteamsocialnetworks[type]['title'];

            item = item.replace(/##icon##/, icon);
            item = item.replace(/##url##/, url);
            item = item.replace(/##title##/, title);
            item = item.replace(/##index##/g, item_count);

            $("#cbxteam-custom-repeatable").append(item);
            $('#cbxteamsocial-type-select-' + item_count).val(type);
            item_count++;
            return false;
        });


        $('#cbxrmmetabox_team_wrapper').on("click", ".cbxteam-repeatable-remove", function () {

            if ($('.cbxteam-repeatable-remove').length > 0) {
                $(this).parent().remove();
                item_count--;
                return false;
            }

        });

        //Sortable Action
        if ($('.cbxteam-custom-repeatable').length) {
            $('.cbxteam-custom-repeatable').sortable({
                opacity: 0.6,
                revert: true,
                cursor: 'move',
                handle: '.cbxteam-sort',
                placeholder: "dashed-placeholder"
            });

        }

    });

})(jQuery);
