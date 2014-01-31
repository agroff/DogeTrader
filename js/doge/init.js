function dbg(variable) {
    console.log(variable);
}

doge = {};

doge.data = {
    currentPrice : "",
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