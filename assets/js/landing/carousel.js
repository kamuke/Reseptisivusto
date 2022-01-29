import{convertCookingTime, convertServings, convertDifficulty} from "../export/convert-info-export.js";
import{getCardHtml} from "../export/html-export.js";

$(document).ready(function () {
    carousel();
});

function carousel() {
    getCarousel(1);

    let direction = -1;
    const slider = $(".slider");
    const nextButton = $(".carousel-button-next");
    const previousButton = $(".carousel-button-previous");

    if ($(window).width() < 768) {
        carouselButtonsMobile();
    } else {
        carouselButtonsDesktopTablet();
    }

    $(window).on("resize", function () {
        const win = $(this);

        if (win.width() < 768) {
            carouselButtonsMobile();
        } else {
            carouselButtonsDesktopTablet();
        }
    });

    slider.on('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd', function (e) {

        if (direction === 1 && $(e.target).closest(".card").length === 0) {
            slider.prepend($(".carousel-card").last());
        } else if (direction === -1 && $(e.target).closest(".card").length === 0) {
            slider.append($(".carousel-card").first());
        }

        slider.css("transition", "none");
        slider.css("transform", "translate(0)");
        setTimeout(() => {
            slider.css("transition", "all 0.5s");
        })
    });

    function carouselButtonsDesktopTablet() {

        nextButton.on("click", function () {

            if (direction === 1) {
                const carouselCard = $(".carousel .card");
                direction = -1;
                slider.css("transform", "translate(-14.6%)");
                $(".carousel").css("justify-content", "flex-start");
                slider.prepend(carouselCard.last());
                slider.prepend(carouselCard.eq(4));

            } else {
                direction = -1;
                $(".carousel").css("justify-content", "flex-start");
                slider.css("transform", "translate(-14.6%)");
            }
        });

        previousButton.on("click", function () {

            if (direction === -1) {
                const carouselCard = $(".carousel .card");
                direction = 1;
                slider.css("transform", "translate(14.6%)");
                $(".carousel").css("justify-content", "flex-end");
                slider.append(carouselCard.first());
                slider.append(carouselCard.eq(1));
            } else {
                $(".carousel").css("justify-content", "flex-end");
                slider.css("transform", "translate(14.6%)");
            }
        });
    }

    function carouselButtonsMobile() {

        nextButton.on("click", function () {

            if (direction === 1) {
                const carouselCard = $(".carousel .card");
                direction = -1;
                slider.css("transform", "translate(-14.6%)");
                $(".carousel").css("justify-content", "flex-start");
                slider.prepend(carouselCard.last());
                slider.prepend(carouselCard.eq(4));
            } else {
                direction = -1;
                $(".carousel").css("justify-content", "flex-start");
                slider.css("transform", "translate(-14.6%)");
            }
        });

        previousButton.on("click", function () {

            if (direction === -1) {
                direction = 1;
                slider.css("transform", "translate(14.6%)");
                $(".carousel").css("justify-content", "flex-end");
                slider.append($(".carousel-card").first());
            } else {
                $(".carousel").css("justify-content", "flex-end");
                slider.css("transform", "translate(14.6%)");
            }
        });
    }

    function getCarousel(ID) {
        const seasonalSection = $(".seasonal-section");
        $.ajax({
            url: "api/carousel/read-single.php?id=" + ID,
            contentType: "application/json",
            dataType: 'json',
            success: function(response) {
                renderCarouselCards(response);
            },
            error: function() {
                seasonalSection.empty();
            }
        });
    }
    
    function renderCarouselCards(data) {
        $(".carousel-headline").html(data.name);
        const card = getCardHtml();

        for (let i = 0; i < data.recipes.length; i++) {
            slider.append(card);
            $(".slider .card:eq(" + i + ") a").attr("href", "recipe.php?id=" + data.recipes[i].ID);
            $(".slider .card:eq(" + i + ") .card-img").attr("src", "assets/images/" + data.recipes[i].img);
            $(".slider .card:eq(" + i + ") .card-img").attr("alt", data.recipes[i].name);
            $(".slider .card:eq(" + i + ") .card-category").html(data.recipes[i].type);
            $(".slider .card:eq(" + i + ") .headline-card").html(data.recipes[i].name);
            $(".slider .card:eq(" + i + ") .card-desc p").html(data.recipes[i].description);
            $(".slider .card:eq(" + i + ") .cooking").html(convertCookingTime(data.recipes[i].cookingtime));
            $(".slider .card:eq(" + i + ") .servings").html(convertServings(data.recipes[i].servings));
            $(".slider .card:eq(" + i + ") .difficulty").html(convertDifficulty(data.recipes[i].difficulty));
            $(".slider .card:eq(" + i + ")").addClass("carousel-card");
            renderCardStars(data.recipes[i].ratingValue, i);
        }
    }
    
    function renderCardStars(rating, counter) {
        const max = Math.round(Number(rating)) - 1;
        for (let i = 0; i <= max; i ++) {
            $(".slider .card:eq(" + counter + ") .star-icon:eq(" + i + ")").css('color', '#E5502F');
        }
    }
}

export{carousel};