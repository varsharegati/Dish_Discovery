<style>
    /* General Styles */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    h1, h2 {
        font-family: 'Brush Script MT', 'Brush Script Std', cursive;
    }
    h1 {
        font-style: italic;
    }

    /* Section Styles */
    .about-page section {
        margin-bottom: 50px;
        padding: 30px 0;
    }

    /* Text Alignment */
    .text-center {
        text-align: center;
    }

    /* Images */
    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    .rounded {
        border-radius: 8px;
    }

    .rounded-circle {
        border-radius: 50%;
    }

    /* Buttons */
    .btn-primary {
        background-color: #ffc107;
        border: none;
    }
    .btn-primary:hover {
        background-color: #e0a800;
    }

    /* Lists */
    .list-unstyled li {
        margin: 10px 0;
        font-size: 18px;
    }

    .list-unstyled li i {
        color: #ffc107;
        margin-right: 10px;
    }

    /* Backgrounds */
    .bg-light {
        background-color: #f8f9fa;
    }

    /* Animations */
    .wow {
        visibility: hidden;
    }
</style>

<div class="container py-5 mt-4">
    <h1 class="text-center strikeBg wow slideInRight">About Us</h1>
    <div id="content" style="display:none;">
        <?php echo html_entity_decode(file_get_contents('about.html')) ?>
    </div>
</div>

<script>
    $(function(){
        // Initialize WOW.js for animation effects
        new WOW().init();

        var content = $('#content');
        var cloned = content.clone();
        var el = $('<div id="content-show">');
        
        // Add animation classes to elements
        cloned.find('p, h1, h2, h3, h4, h5, ul, ol, img').each(function(){
            $(this).addClass("wow fadeIn");
        });
        
        el.append(cloned.html());
        content.replaceWith(el);
    });
</script>
