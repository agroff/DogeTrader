/******************************************
 * Alarm
 ******************************************/

doge.alarm = {};

doge.alarm.celebrate = function () {
    var rocket = $("#rocket"),
        top = $(window).height(),
        body = $("body"),
        song = $('<iframe width="0" height="0" src="//www.youtube-nocookie.com/embed/21LA888yC48?autoplay=1" frameborder="0" allowfullscreen></iframe>');

    window.scrollTo(0,0);

    $(".celebrate").show();
    $("#moon-button").hide();
    body.addClass("space");

    rocket.css({
        top  : top,
        left : 0
    });

    setTimeout(function () {
        rocket.animate({
            top  : 80,
            left : $(window).width() - 120
        }, 40000);
    }, 2000)

    setTimeout(function () {
        song.remove();
        $(".celebrate").hide();
        body.removeClass("space")
        $("#moon-button").show();
    }, 50000)

    body.append(song);
};

doge.alarm.info = function (id, attribute) {
    var alarm = {};
    attribute = attribute || false;

    $.each(doge.settings.alarms, function(i, item){
        if(item.id == id){
            alarm = item;
        }
    });

    if(attribute === false){
        return alarm;
    }

    return alarm[attribute];
}

doge.alarm.render = function () {
    var $alarmList = $("#alarmList"),
        $triggeredAlarmList = $("#triggeredAlarmList");

    $alarmList.html("");
    $triggeredAlarmList.html("");
    $.each(doge.data.alarms, function(i, item){
        if(item.triggered){
            $triggeredAlarmList.loadTemplate($("#triggeredAlarmTemplate"), item, {prepend: true});
        }
        else {
            $alarmList.loadTemplate($("#alarmTemplate"), item, {append : true});
        }

    })

}

doge.alarm.check = function (newPrice) {

    $.each(doge.data.alarms, function(i,item){

        if(item.triggered) {
            return;
        }

        if(item.initialValue >= item.satoshi && newPrice <= item.satoshi){
            doge.alarm.trigger(item);
            return;
        }

        if (item.initialValue <= item.satoshi && newPrice >= item.satoshi){
            doge.alarm.trigger(item);
        }
    })
}

doge.alarm.playAudio = function(youtubeId, time){
    var audio = $('<iframe width="0" height="0" src="//www.youtube-nocookie.com/embed/'+youtubeId+'?autoplay=1" frameborder="0" allowfullscreen></iframe>')
    $("body").append(audio);

    setTimeout(function () {
        audio.remove();
    }, time * 1000)
}

doge.alarm.trigger = function (alarm) {
    var alarmData = doge.alarm.info(alarm.alarm_id);

    $.each(doge.data.alarms, function(i,item){
        if(alarm.id === item.id){
            doge.data.alarms[i].triggered = true;
            doge.data.alarms[i].triggered_time = doge.utils.minutesAgo(new Date());
        }
    });

    if(alarm.alarm_id == "moon-party") {
        doge.alarm.celebrate();
    }
    else {
        doge.alarm.playAudio(alarmData.youtube, alarmData.length);
    }

    doge.storeData();
    doge.alarm.render();
}

doge.alarm.add = function () {
    var u = doge.utils,
        satoshi = $("#alarm-satoshi").val(),
        id = $("#alarm-id").val(),
        current = doge.lastPrice.get();

    satoshi = u.toNumber(satoshi);

    if(satoshi === false) {
        doge.utils.error("Please enter the satoshi value that you'd like to be alarmed about.");
        return;
    }

    if(id==="") {
        doge.utils.error("Please choose an alarm sound");
        return;
    }

    doge.data.alarms.push({
        satoshi: satoshi,
        alarm_id: id,
        triggered: false,
        name: doge.alarm.info(id, "name"),
        initialValue: current,
        id: u.time()
    });

    doge.storeData();
    doge.alarm.render();
}

doge.alarm.remove = function (id) {
    var newAlarms = [];
    $.each(doge.data.alarms, function(i,item){
        if(item.id != id){
            newAlarms.push(item);
        }
    });
    doge.data.alarms = newAlarms;
    doge.storeData();
    doge.alarm.render();
};

doge.alarm.bind = function () {
    var remove = function() {
        var id = $(".alarmId", $(this).parent().parent()).text();
        doge.alarm.remove(id);
    };

    $("#new-alarm").click(doge.alarm.add);
    doge.lastPrice.bindNewPrice(this.check);

    $( "#alarmList" ).on( "click", ".removeAlarm", remove);
    $( "#triggeredAlarmList" ).on( "click", ".removeAlarm", remove);
}