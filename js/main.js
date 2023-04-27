jQuery(document).ready(function ($) {

    $("body").on("click", ".taxitornot", function () {
        let taxit = $(this).data("taxit");
        let ancid = $(this).closest(".sg-popup-builder-content").data("id");

        $.ajax({
            url: snpopjboost_tax.ajax_url,
            type: "post",
            data: {
                action: "snpopjboost_tax_display",
                taxit: taxit,
            },
            success: function (response) {
                //SGPBPopup.closePopupById(ancid); //to close popup if using popup builder
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            },
        });
    });
});