function dbg(variable) {
    console.log(variable);
}

doge = {};

doge.data = {
    currentPrice : "",
    analyzeCount : 200,
    changeLog    : [],
    buys         : [],
    alarms       : []
}

/*
 * Populated by /settings.json
 * structure is there for autocomplete and reference.
 */
doge.settings = {
    api   : {
        "url": "",
        "refresh_time": ""
    },
    price : {
        average_count : ""
    }
};