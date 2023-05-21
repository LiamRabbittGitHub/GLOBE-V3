<?php

/**
 *
 * Template Name: Download Template
 */
if (!empty($_POST['serial'])) {
    $serial = $_POST['serial'];

    // ...

    if ($status == 'completed') {

        // ...

    } else {
        echo "<script type=\"text/javascript\">window.alert('Your order is under process for now!!');
        window.location.href = 'download/';</script>";
    }
}

get_header(); ?>

<?php if (function_exists('pf_show_link')) {
    echo pf_show_link();
} ?>

<!-- ... -->

<?php get_footer(); ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        function screenshot() {
            const mainTemplate = document.querySelector('.main-template');
            const img = document.querySelector('.main-img');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const link = document.createElement('a');

            canvas.width = mainTemplate.clientWidth;
            canvas.height = mainTemplate.clientHeight;

            function drawImageAndDownload() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                // Add other drawings to the canvas here, if necessary

                canvas.toBlob((blob) => {
                    link.href = URL.createObjectURL(blob);
                    link.download = 'screenshot.png';
                    link.click();
                });
            }

            if (img.complete) {
                drawImageAndDownload();
            } else {
                img.addEventListener('load', drawImageAndDownload);
            }
        }

        const screenshotButton = document.getElementById('but_screenshot');
        if (screenshotButton) {
            screenshotButton.addEventListener('click', screenshot);
        }
    });
</script>
