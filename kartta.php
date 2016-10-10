
<html>
    <head>
        <meta name="viewport" content="width=1200, height=700, initial-scale=1">
        <title>L‰hiliikenne</title>
        <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="//code.createjs.com/easeljs-0.8.1.min.js"></script>
        <script>
        var d2r = (Math.PI / 180);
        var ratalines = {
            "0": {
                'line': [
                    [190, 165],
                    [215, 100],
                    [1150, 100]
                ],
                'color': 'rgb(100, 190, 20)',
                stations: []
            },
            "1": {
                'line': [
                    [190, 165],
                    [220, 200],
                    [650, 200],
                    [685, 215],
                    [700, 240],
                    [700, 340],
                    [690, 360],
                    [670, 370],
                    [600, 370],
                    [550, 400]
                ],
                'color': 'rgb(140, 71, 153)',
                stations: []
            },
            "2": {
                'line': [
                    [135, 250],
                    [160, 165],
                    [190, 165]
                ],
                'color': 'rgb(140, 71, 153)',
                stations: []
            },
            "3": {
                'line': [
                    [135, 250],
                    [190, 400],
                    [550, 400]
                ],
                'color': 'rgb(0, 122, 201)',
                stations: []
            },
            "4": {
                'line': [
                    [25, 250],
                    [135, 250]
                ],
                'color': 'rgb(140,71,153)',
                stations: []
            },
            "5": {
                'line': [
                    [550, 400],
                    [1050, 400]
                ],
                'color': 'rgb(0, 122, 201)',
                stations: [],
            },
        }

        Math.hypot = Math.hypot || function() {
            var y = 0;
            var length = arguments.length;

            for (var i = 0; i < length; i++) {
                if (arguments[i] === Infinity || arguments[i] === -Infinity) {
                    return Infinity;
                }
                y += arguments[i] * arguments[i];
            }
            return Math.sqrt(y);
        };

        var dist = function(a, b) {
            //return (a[0]-b[0])*(a[0]-b[0])+(a[1]-b[1])*(a[1]-b[1]);
            return Math.hypot(a[0] - b[0], a[1] - b[1]);
        }

        var calcLineLength = function(rid) {
            var line = ratalines[rid].line;
            var llength = ratalines[rid].length;
            if (llength == undefined) {
                llength = 0;
                for (var i = 1; i < line.length; i++) {
                    llength += dist(line[i - 1], line[i]);
                }
                ratalines[rid].length = llength;
            }

            return llength;
        }
        var interpolate = function(rid, ratio) {
            var line = ratalines[rid].line;
            if (Math.abs(ratio < 0.000001)) {
                return line[0];
            }
            if (Math.abs(ratio - 1) < 0.000001) {
                return line[line.length - 1];
            }

            ratio = Math.max(Math.min(ratio, 1.0), 0.0);
            //console.log('l',line);
            //console.log('r',ratio);

            llength = calcLineLength(rid);

            var rdist = ratio * llength;
            //console.log('rdist',rdist);
            var a = line[0],
                b = line[1],
                dA = 0;
            dB = dist(a, b);
            var index = 1;
            for (; index < line.length && dB < rdist; index++) {
                a = b;
                dA = dB;
                b = line[index];
                dB += dist(a, b);
            }
            //console.log('dA',dA,'dB',dB,'index',index);

            var sratio = ((dB - dA) !== 0) ? ((rdist - dA) / (dB - dA)) : 0;
            //console.log('sratio',sratio);
            return [
                (a[0] * (1 - sratio)) + (sratio * b[0]), (a[1] * (1 - sratio)) + (sratio * b[1])
            ];
        };

        $(function() {
            var stage = new createjs.Stage("dcanv");
            stage.enableMouseOver(20);
            createjs.Touch.enable(stage,false,true);
            stage.preventSelection = false;

            var stations;
            var trains = [];
            var loadData = function(dfunc) {
                $.getJSON('http://liikenne.hylly.org/rata/lahi/traininfo.json?' + new Date().getTime(), function(d) {
                    console.log(d.updated);
                    $('#upd').html('P‰ivitetty: ' + d.updated);
                    dfunc(d.trains);
                });
            };


            //loadData();
            var drawInfra = function() {
                stage.update();
                $.each(Object.keys(ratalines), function(rid) {
                    var l = ratalines[rid];
                    if (l === undefined) return true;
                    var pathstr = [];

                    var pathobj = new createjs.Shape();
                    var trackline = pathobj.graphics;
                    trackline.s(l.color).ss(6).mt(l.line[0][0], l.line[0][1]);
                    for (var i = 1; i < l.line.length; i++) {
                        trackline.lt(l.line[i][0], l.line[i][1]);
                    }
                    trackline.es();

                    stage.addChild(pathobj);
                    stage.setChildIndex(pathobj, 0);

                    ratalines[rid].pathobj = pathobj;

                    l.stations.forEach(function(sk) {
                        var s = stations[sk];

                        var ll = calcLineLength(rid);
                        var pos = interpolate(rid, s.rpos);
                        var px = parseInt(pos[0]),
                            py = parseInt(pos[1]);


                        var stationshape = new createjs.Shape();

                        var sg = stationshape.graphics;

                        sg.beginStroke('black').setStrokeStyle(2);
                        sg.beginFill('white');
                        sg.drawCircle(px, py, 6);


                        var strotation = 45;
                        var st = new createjs.Text(s.n !== undefined ? s.n : sk, "bold 10px Tahoma", '#000000');



                        st.x = px;
                        st.y = py;

                        var xd = Math.cos(strotation * d2r) * st.getMeasuredWidth();
                        var yd = Math.sin(strotation * d2r) * st.getMeasuredWidth();


                        st.rotation = strotation;

                        if (rid == "3" || rid == "5" || sk == "lentoasema" || sk == "aviapolis" || sk == "pohjois-haaga" || sk == 'helsinki') {
                            st.y = st.y + 12;
                            st.x = st.x;
                            if (sk == 'lentoasema' || sk == 'aviapolis') {
                                st.x += 12;
                                st.y -= 12;
                            } else if (sk == 'k‰pyl‰') {
                                st.x = px + 12;
                                st.y = py - st.getMeasuredHeight() / 2;
                                st.rotation = 0;
                            }
                        } else {

                            st.y = st.y - yd - 14;
                            st.x = st.x - xd;

                        }
                        var stationcont = new createjs.Container();

                        stationshape.zIndex = 2;
                        stationcont.addChild(stationshape);
                        stationcont.addChild(st);
                        stage.addChild(stationcont);
                    });

                });

                stage.update();

            }

            var loadStations = function(cb) {
                $.getJSON('http://liikenne.hylly.org/rata/lahi/stations2.json', function(sts) {
                    stations = sts;
                    $.each(sts, function(k, v) {
                        if (ratalines[v.rosa] === undefined) return true;
                        if (v.rpos === null) return true;
                        ratalines[v.rosa].stations.push(k);
                    });


                    if (cb !== undefined) {
                        cb();
                    }
                });
            };
            var testpos = 0.6;
            var drawTrains = function(dtr) {
                Object.keys(trains).forEach(function(tn) {
                    stage.removeChild(trains[tn].gobj);
                });
                trains = {};

                $.each(dtr, function(k, t) {

                    var rlen = calcLineLength(t.rid);

                    var ppos = interpolate(t.rid, Math.max(0, t.pos - (10 / rlen)));
                    var npos = interpolate(t.rid, Math.min(1, t.pos + (10 / rlen)));

                    var dir = Math.atan2(ppos[1] - npos[1], ppos[0] - npos[0]);
                    var dirdelta = 30;
                    dir = dir + (2 * Math.PI);
                    dir = dir % (2 * Math.PI);
                    dir += t.dir * (Math.PI / 2);

                    dirdx = Math.cos(dir) * dirdelta;
                    dirdy = Math.sin(dir) * dirdelta;

                    var pos = interpolate(t.rid, t.pos);
                    var px = parseInt(pos[0]),
                        py = parseInt(pos[1]);

                    var tcont = new createjs.Container();

                    var tshape = new createjs.Shape();
                    tshape.graphics.ss(1.5).s('white').f('black').dc(px, py, 4).es();


                    var bgcolor = '#D9D9D9';
                    if (t.next) {
                        bgcolor = '#abdda4';
                        if (t.next.diff > 2) bgcolor = '#fdae61';
                        else if (t.next.diff > 10) bgcolor = '#d7191c';
                    }

                    tshape.graphics.ss(1.5).s('black').mt(px, py).lt(px + dirdx, py + dirdy).es();
                    tshape.graphics.ss(1.5).s(!t.interpolate ? 'black' : 'red').f(bgcolor).dc(px + dirdx, py + dirdy, 15).es();


                    if (t.next) {
                        var tsize = 15;
                        var ttriag = new createjs.Shape();

                        var tangle = (dir + (Math.PI)) % (Math.PI * 2);
                        var tddx = Math.cos(tangle + (Math.PI / 2)) * tsize;
                        var tddy = Math.sin(tangle + (Math.PI / 2)) * tsize;

                        var tw = 20;
                        var th = 20;
                        ttriag.graphics.ss(1).s('black').f(bgcolor).mt(0, 0).lt(0, th).lt(tw, th / 2).cp().es().ef();

                        ttriag.regX = tw / 2;
                        ttriag.regY = th / 2;

                        ttriag.x = px + dirdx - tddx;
                        ttriag.y = py + dirdy - tddy;
                        ttriag.rotation = tangle / d2r - 90;


                        tcont.addChild(ttriag);
                    }


                    var ttext = new createjs.Text((t.id !== "" ? t.id : t.tn), "bold 12px Verdana", 'black');

                    ttext.x = px + dirdx - ttext.getMeasuredWidth() / 2;
                    ttext.y = py + dirdy - ttext.getMeasuredHeight() / 2;

                    tcont.addChild(tshape);
                    tcont.addChild(ttext);

                    tcont.cursor = 'pointer';
                    tshape.cursor = 'pointer';


                    var showInfoPopup = function(e, t) {
                        var px = e.rawX + 20;
                        var py = e.rawY - 25;

                        var w = $('#info').width();
                        var h = $('#info').height();

                        var ww = $(window).width();
                        var wh = $(window).height();

                        if (px + w > ww) {
                            px = e.rawX - w - 20;
                        }

                        $('#info').css({
                            'left': px,
                            'top': py
                        });
                        $('#info').html('<strong>' + t.tn + ' ' + t.id + '</strong>' + (t.next ? '<br/><small>Seuraavaksi: <strong>' + t.next.station + ' (' + t.next.time + (t.next.diff > 0 ? ' / +' + t.next.diff + ' min)' : ')') + '</strong><br/>M‰‰r‰asema: <strong>' + t.last.station + ' (' + t.last.time + (t.last.diff > 0?' / +' + t.last.diff + ' min)':')') + '</small>' : ''));
                        $('#info').show();

                        return true;
                    };

                    tcont.on('mouseover', showInfoPopup, null, false, t);
                    tcont.on('click', showInfoPopup, null, false, t);

                    tcont.on('mouseout', function(e, t) {
                        $('#info').hide();
                    });
                    t.gobj = tcont;
                    stage.addChild(tcont);
                    trains[t.tn] = t;
                });
                stage.update();
            }


            var updateTrains = function() {
                loadData(drawTrains);
                testpos += 0.1;

                if (testpos > 1) {
                    testpos = 0.0;
                }

                setTimeout(updateTrains, 20000);
            }


            loadStations(function() {
                drawInfra();
                loadData(drawTrains);
                testpos += 0.1;
                setTimeout(updateTrains, 20000);
            });

            $('#info').hide();


        });
        </script>
        <style>
        #upd {
            font-size:11px;
            font-family:tahoma;
        }

        #info {
            position:absolute;
            border:1px solid black;
            background-color:white;
            border-radius: 5px;
            width:250px;
            height:38px;
            font-size:12px;
            font-family:tahoma;
            padding:3px;
        }
        </style>
    </head>
    <body>

        <canvas id="dcanv" width="1200" height="500"></canvas>
        <div id="upd"></div>
        <div id="info"></div>
    </body>
</html>