function convertCookingTime(time) {
    const number = Number(time);

    switch (true) {
        case (number < 1):
            return "Ei valmistusaikaa.";
        case (number < 60):
            return number + " min";
        case (number >= 60):
            const hours = (time / 60);
            const rhours = Math.floor(hours);
            const minutes = (hours - rhours) * 60;
            const rminutes = Math.round(minutes);

            if (rminutes === 0) {
                return rhours + " h";
            }

            return rhours + " h " + rminutes + " min";
        default:
            return "Ei valmistusaikaa."
    }
}

function convertServings(servings) {
    const number = Number(servings);

    switch (true) {
        case number === 1:
            return number + " annos";
        case number > 1:
            return number + " annosta";
        default:
            return "Ei annosmäärää.";
    }
}

function convertDifficulty(difficulty) {
    const number = Number(difficulty);

    switch (true) {
        case number === 1:
            return "Helppo";
        case number === 2:
            return "Keskivaikea";
        case number === 3:
            return "Vaikea";
        default:
            return "Ei vaikeusastetta";
    }
}

function convertDate(timestamp) {
    let datetime = new Date(timestamp),
        day = '' + datetime.getDate(),
        month = '' + (datetime.getMonth() + 1),
        year = datetime.getFullYear();

    if (day.length < 2) {
        day = '0' + day;
    }

    if (month.length < 2) {
        month = '0' + month;
    }
    
    return [day, month, year].join('.');
}

function convertRatingCount(count) {
    const number = Number(count);

    switch (true) {
        case number === 1:
            return number + " arvostelu";
        case number > 1:
            return number + " arvostelua";
        default:
            return "Ei arvosteluja";
    }
}

function roundRatingValue(rating) {
    const number = Number(rating);
    return (Math.round(number * 10) / 10);
}

export{convertCookingTime, convertServings, convertDifficulty, convertDate, convertRatingCount, roundRatingValue};