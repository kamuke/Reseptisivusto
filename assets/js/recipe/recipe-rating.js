import{convertRatingCount, roundRatingValue} from "../export/convert-info-export.js";

$(document).ready(function () {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ID = urlParams.get('id');
    const ratingValue = $(".rating-value");
    const ratingCount = $(".rating-count");
    const ratingValueCount = $(".rating-value-count");
    let ratedIndex;

    $(".stars .star-icon").on("mouseenter", function () {
        resetStars();
        const currentIndex = parseInt($(this).data('index'));
        renderStars(currentIndex);
    });

    $(".stars .star-icon").on("mouseleave", function () {
        resetStars();
        renderStars(Math.round($(".rating-value").text()) - 1);
    });

    $(".stars .star-icon").on("click", function () {
        ratedIndex = parseInt($(this).data('index'));
        saveToTheDB();
    });

    function renderStars(max) {
        for (let i = 0; i <= max; i++) {
            $(".stars .star-icon:eq(" + i + ")").css('color', '#E5502F');
        }
    }

    function resetStars() {
        $(".stars .star-icon").css('color', '#8D8D8D');
    }

    function saveToTheDB() {
        $.ajax({
            url: "api/recipe/add-rating.php",
            contentType: "application/json",
            type: "POST",
            data: JSON.stringify({
                ID: ID,
                ratedIndex: ratedIndex
            }),
            success: function (response) {
                if (response.ratingValue) {
                    ratingValue.html(roundRatingValue(response.ratingValue));
                    ratingCount.html("(" + convertRatingCount(response.ratingCount) + ")");
                    ratingValueCount.prepend(`<div>Kiitos arvostelusta!</div>`);
                }

                console.log(response);
            },
            error: function() {
                console.log("Error");
            }
        });
    }
});