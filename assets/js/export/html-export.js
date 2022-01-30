function getCardHtml() {
    const cardHtml =
        '<article class="card round-box drop-shadow">\n' +
            '<a href="">\n' +
                '<figure class="card-thumbnail">\n' +
                    '<img class="card-img" src="" alt="">\n' +
                '</figure>\n' +
                '<div class="card-content">\n' +
                    '<div class="card-category"></div>\n' +
                    '<div class="card-rating">\n' +
                        '<div class="star-icon"><i class="fas fa-star"></i></div>\n' +
                        '<div class="star-icon"><i class="fas fa-star"></i></div>\n' +
                        '<div class="star-icon"><i class="fas fa-star"></i></div>\n' +
                        '<div class="star-icon"><i class="fas fa-star"></i></div>\n' +
                        '<div class="star-icon"><i class="fas fa-star"></i></div>\n' +
                    '</div>\n' +
                    '<header>\n' +
                        '<h3 class="headline-card"></h3>\n' +
                    '</header>\n' +
                    '<div class="card-desc">\n' +
                        '<p></p>\n' +
                    '</div>\n' +
                '</div>\n' +
                '<footer class="card-info">\n' +
                '<div class="cooking-time-container">\n' +
                '<div class="clock-icon">\n' +
                '<i class="far fa-clock"></i>\n' +
                '</div>\n' +
                '<span class="cooking"></span>\n' +
                '</div>\n' +
                '<div class="servings-container">\n' +
                '<div class="utensils-icon">\n' +
                '<img src="assets/svgs/utensils-icon.svg" alt="Utensils icon">\n' +
                '</div>\n' +
                '<span class="servings"></span>\n' +
                '</div>\n' +
                '<div class="difficulty-container">\n' +
                '<div class="chef-hat-icon">\n' +
                '<img src="assets/svgs/chef-hat-icon.svg" alt="Chef hat icon">\n' +
                '</div>\n' +
                '<span class="difficulty"></span>\n' +
                '</div>\n' +
                '</footer>\n' +
            '</a>\n' +
        '</article>';

    return cardHtml;
}

function getSeasonalAndNewSectionHtml() {
    const html =
        '<section class="seasonal-section">\n' +
            '<div class="container">\n' +
            '   <h2 class="carousel-headline"></h2>\n' +
            '</div>\n' +
            '<div class="carousel-container">\n' +
                '<div class="carousel">\n' +
                    '<div class="slider"></div>\n' +
                '</div>\n' +
                '<div class="carousel-buttons">\n' +
                    '<button class="carousel-button-previous drop-shadow" type="button" name="button"><i class="fas fa-chevron-left"></i></button>\n' +
                    '<button class="carousel-button-next drop-shadow" type="button" name="button"><i class="fas fa-chevron-right"></i></button>\n' +
                '</div>\n' +
            '</div> \n' +
        '</section>\n' +
        '<section class="new-section">\n' +
            '<div class="container">\n' +
                '<h2>Uusimmat reseptit ja ateriakokonaisuudet</h2>\n' +
            '</div>\n' +
            '<div class="new-recipes card-container"></div>\n' +
            '<div class="container">\n' +
                '<button id="show-more" class="button-primary drop-shadow" type="button" name="button">\n' +
                    '<span class="button-text">Näytä lisää</span>\n' +
                '</button>\n' +
            '</div>\n' +
        '</section>';

    return html;
}

export{getCardHtml, getSeasonalAndNewSectionHtml};

