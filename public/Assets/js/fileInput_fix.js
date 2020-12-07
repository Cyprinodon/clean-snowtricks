"use strict";

(($) => {
        let fileInput = $("input[type='file']");
        let filename;
        fileInput.on('change', ()=>{ filename = $(this).val() });
        fileInput.value = filename;
    }
)(jQuery);