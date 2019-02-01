        $(".changebg-phil").hover(function () {
            $('#img-switch').css("background-image", "url('/assets/images/groupe4/Phil.png')");
            $('.hidden-div-phil').css("display", "block");
            $('.hidden-div-emer, .hidden-div-dave, .hidden-div-alex, .hidden-div-jeff, .hidden-div-kev').css("display", "none");
        });

        $(".changebg-emer").hover(function () {
            $('#img-switch').css("background-image", "url('/assets/images/groupe4/Emer.png')");
            $('.hidden-div-emer').css("display", "block");
            $('.hidden-div-phil, .hidden-div-dave, .hidden-div-alex, .hidden-div-jeff, .hidden-div-kev').css("display", "none");
        });

        $(".changebg-dave").hover(function () {
            $('#img-switch').css("background-image", "url('/assets/images/groupe4/Dave.png')");
            $('.hidden-div-dave').css("display", "block");
            $('.hidden-div-phil, .hidden-div-emer, .hidden-div-alex, .hidden-div-jeff, .hidden-div-kev').css("display", "none");
        });

        $(".changebg-alex").hover(function () {
            $('#img-switch').css("background-image", "url('/assets/images/groupe4/Alex.png')");
            $('.hidden-div-alex').css("display", "block");
            $('.hidden-div-phil, .hidden-div-emer, .hidden-div-dave, .hidden-div-jeff, .hidden-div-kev').css("display", "none");
        });

        $(".changebg-jeff").hover(function () {
            $('#img-switch').css("background-image", "url('/assets/images/groupe4/Jeff.png')");
            $('.hidden-div-jeff').css("display", "block");
            $('.hidden-div-phil, .hidden-div-emer, .hidden-div-dave, .hidden-div-alex, .hidden-div-kev').css("display", "none");
        });

