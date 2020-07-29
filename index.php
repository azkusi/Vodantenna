

<!DOCTYPE html>
<html>



 <style>
.dot1 {
  height: 25px;
  width: 25px;
  background-color: #d93954;
  border-radius: 50%;
  display: block;
}

.dot2 {
  height: 25px;
  width: 25px;
  background-color: #ea909f;
  border-radius: 50%;
  display: block;
}

.dot3 {
  height: 25px;
  width: 25px;
  background-color: #ffc966;
  border-radius: 50%;
  display: block;
}

.dot4 {
  height: 25px;
  width: 25px;
  background-color: #ffe4b2;
  border-radius: 50%;
  display: block;
}

.dot5 {
  height: 25px;
  width: 25px;
  background-color: #ffff00;
  border-radius: 50%;
  display: block;
}

.dot6 {
  height: 25px;
  width: 25px;
  background-color: #ffff7f;
  border-radius: 50%;
  display: block;
}

.dot7 {
  height: 25px;
  width: 25px;
  background-color: #00ff00;
  border-radius: 50%;
  display: block;
}

.dot8 {
  height: 25px;
  width: 25px;
  background-color: #0000ff;
  border-radius: 50%;
  display: block;
}

.dot9 {
  height: 25px;
  width: 25px;
  background-color: #704876;
  border-radius: 50%;
  display: block;
}


.col-sm-2 > span, .col-sm-2 > p {
  display: inline-block;
}



</style>


<body>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>

<div>

<div style="display: flex; justify-content: space-around">

<!--set the canvas for people to draw on-->
    <div>
        <canvas id="myCanvas" width=70% height=70% style="border:1px solid">
            Your browser does not support the HTML5 canvas tag.
        </canvas>
    </div>


    <div>

<!--set the key, showing the mapping of colours to field strength-->
    <div class="row">

        <div class="col-sm-2"> <span><div class="dot1"></div></span>
            <p>Field Strength (0m from antenna)</p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2"> <span><div class="dot2"></div></span>
            <p>Field Strength/100 (10m from antenna)</p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2"> <span><div class="dot3"></div></span>
            <p>Field Strength/400 (20m from antenna)</p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2"> <span><div class="dot4"></div></span>
            <p>Field Strength/900 (30m from antenna)</p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2"> <span><div class="dot5"></div></span>
            <p>Field Strength/1600 (40m from antenna)</p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-2"> <span><div class="dot6"></div></span>
            <p>Field Strength/2500 (50m from antenna)</p>
        </div>

    </div>




    </div>

    <div>
        <div class="row">
            <div class="col-sm-2"> <span><div class="dot7"></div></span>
                <p>Field Strength/3600 (60m from antenna)</p>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2"> <span><div class="dot8"></div></span>
                <p>Field Strength/4900 (70m from antenna)</p>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2"> <span><div class="dot8"></div></span>
                <p>Field Strength/6400 (80m from antenna)</p>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2"> <span><div class="dot9"></div></span>
                <p>Field Strength/8100 (90m from antenna)</p>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2"> <span><div class="dot9"></div></span>
                <p>Field Strength/10000 (100m from antenna)</p>
            </div>

        </div>
    </div>


</div>

<!--button elements-->
<p> Select floor plan image to input: <input type="file" id="fUpload" onchange="previewImage(this);"></p>


<div>
    <p> CONTROL PANEL BUTTONS </p>

    <button onclick="PropagationModel();"> Place image on canvas </button>


    <button onclick="drawAntenna();"> Draw antenna location </button>
    <!--<button onclick="diamondapprox();"> Draw Approx </button>-->

    <button onclick="drawPropModel();"> Create propagation model </button>


    <!--<button onclick="drawObjects();"> Draw Map Objects </button>-->


    <!--<button onclick="updateModel();"> Update Propagation Model </button>-->


</div>
    <p> <img id="preview"></p>
</div>


<script type="text/javascript">



var canvas = document.getElementById("myCanvas");
var context = canvas.getContext("2d");


canvas.height = window.innerHeight * 0.8;
canvas.width = window.innerWidth * 0.8;

var img = document.getElementById("preview");

var imageSet = false;

//function that loads preview of image below the canvas
function previewImage(input) {

        var file = document.querySelector("#fUpload");
        if ( /\.(jpe?g|png|gif)$/i.test(file.files[0].name) === false ) {
            alert("Cannot complete upload. Make sure file type is JPEG, JPG, PNG or GIF!");
            return;

        }

        var reader = new FileReader();
        reader.onload = function (e) {
            console.log('hey');
            document.getElementById("preview").setAttribute("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        imageSet = true;
        listChecker +=1 ;
}


//use this variable to count if image inserted into canvas and
//antenna location has been drawn
var listChecker = 0;

var imageWidth;
var imageHeight;


var centerShift_x;
var centerShift_y;
var width_ratio;
var height_ratio;

//function that places image inside canvas, scaled and centred
function drawImageScaled(img, ctx) {


        if (imageSet == true){
               var canvas = ctx.canvas ;
               var hRatio = canvas.width  / img.width    ;
               var vRatio =  canvas.height / img.height  ;
               var ratio  = Math.min ( hRatio, vRatio );
               centerShift_x = ( canvas.width - img.width*ratio ) / 2;
               centerShift_y = ( canvas.height - img.height*ratio ) / 2;
               width_ratio = img.width*ratio ;
               height_ratio = img.height*ratio ;
               ctx.clearRect(0,0,canvas.width, canvas.height);
               ctx.drawImage(img, 0,0, img.width, img.height,
               centerShift_x,centerShift_y, width_ratio, height_ratio);
               imageWidth = img.width;
               console.log("imagewidth 1 is: ", imageWidth);
               imageHeight = img.height;

               listChecker +=1;

        }
        else{
            var setPrompt = alert("You must first Select File, of type JPEG or PNG");

            return;
        }


        return;
}



var canvas = document.getElementById('myCanvas');
var context = canvas.getContext('2d');

var fullSet = [];

//function that allows for draw of rf-blocking objects only evaluates when draw object button is pressed
function drawObjects (){

        var draw = false;
        var coords = [];
        var drawCount = 0;

// on button click set draw to true for use in mousemove function
        canvas.addEventListener('click', function (event) {
            coords = [];
            draw = !draw;
            drawCount = drawCount + 1;
            console.log(coords)
            console.log('coordinates are: ', fullSet)
            console.log('LENGTH of fullSet for object shape: ', fullSet.length)
            console.log("draw count is: ", drawCount)

//          if full shape drawn send to database
            if (drawCount == 2){
                // send fullSet coordinates of drawing to database for object table

                console.log('sending to db in theory')
                xmlhttp = new XMLHttpRequest();
                var nickn = new FormData();
                nickn.append('coordinates', fullSet);

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(xmlhttp.responseText);
                    }
                }

                    xmlhttp.addEventListener('load', function( event ) {
                    alert( 'Yeah! Data sent and response loaded.' );
                    });

                    // Define what happens in case of error
                    xmlhttp.addEventListener('error', function( event ) {
                    alert( 'Cannot connect to database!' );
                    });

                    //send to database
                    xmlhttp.open("POST", "sendDB.php", true);
                    // xmlhttp.setRequestHeader("Content-type",'application/json; charset=utf-8');
                    xmlhttp.send(nickn);

                    //diamondapprox();


                console.log('sending to DB');

                // reset array that collects coordinates of object drawing
                fullSet = []

                console.log('length of fullSet for object shape is ', fullSet.length)


            }


        });
        canvas.addEventListener('mousemove', function (event) {
            if ((draw) && drawCount < 2 ){
                context = canvas.getContext("2d");
                //get coordinates of each mouse movement
                var coord = { 'x': event.x - this.offsetLeft, 'y': event.y - this.offsetTop };
                var coord1 = [event.x - this.offsetLeft, event.y - this.offsetTop]

                //place coordinates for this movement in coords array
                coords.push(coord);

                //update the full coordinate array for full drawing to send to database
                fullSet.push(coord1)
                var max = coords.length - 1;
                if (typeof coords[max - 1] !== "undefined") {
                    var curr = coords[max], prev = coords[max - 1];

                    //draw the image
                    context.beginPath();
                    context.moveTo(prev.x, prev.y);
                    context.lineTo(curr.x, curr.y);
                    context.stroke();
                }
            }


        });


}


// function for drawing antenna

function drawAntenna (){
    if (listChecker >= 2){

        var draw = false;
        var coords = [];
        var drawCount = 0;

        // on button click set draw to true for use in mousemove function
        canvas.addEventListener('click', function (event) {
            coords = [];
            draw = !draw;
            drawCount = drawCount + 1;
            console.log(coords)
            console.log('coordinates are: ', fullSet)
            console.log('LENGTH of fullSet for antenna shape: ', fullSet.length)
            console.log("draw count is: ", drawCount)

            //          if full shape drawn send to database
            if (drawCount == 2){
                console.log('sending to db in theory')
                xmlhttp = new XMLHttpRequest();
                // var nickn = '{"name1": "JordanLeBron"}';
                var nickn = new FormData();
                nickn.append('antennaCoordinates', fullSet);
                //nickn.append('name', 'JordanLeBrontwothree');

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(xmlhttp.responseText);
                    }
                }

                    xmlhttp.addEventListener('load', function( event ) {
                    alert( 'Yeah! Data sent and response loaded.' );
                    });

                    // Define what happens in case of error
                    xmlhttp.addEventListener('error', function( event ) {
                    alert( 'Cannot connect to database!' );
                    });

                    //send to database
                    xmlhttp.open("POST", "antennaDraw.php", true);
                    // xmlhttp.setRequestHeader("Content-type",'application/json; charset=utf-8');
                    xmlhttp.send(nickn);

                    //diamondapprox();


                console.log('sending to DB');

                console.log('length of fullSet for antenna shape is ', fullSet.length)

            //              insert name field for choice of antenna into database

            }


        });
        canvas.addEventListener('mousemove', function (event) {
            if ((draw) && drawCount < 2 ){
                context = canvas.getContext("2d");
                //get coordinates of each mouse movement
                var coord = { 'x': event.x - this.offsetLeft, 'y': event.y - this.offsetTop };
                var coord1 = [event.x - this.offsetLeft, event.y - this.offsetTop]
                coords.push(coord);
                //update the full coordinate array for full drawing to send to database
                fullSet.push(coord1)
                var max = coords.length - 1;
                if (typeof coords[max - 1] !== "undefined") {
                    var curr = coords[max], prev = coords[max - 1];
                    //draw antenna location
                    context.beginPath();
                    context.moveTo(prev.x, prev.y);
                    context.lineTo(curr.x, curr.y);
                    context.stroke();
                }
            }


        });

        listChecker +=1;
        return;

    }

    else{
        var promptUser = alert("Must first upload floorplan image, then place on canvas");
        return;
    }




}


// if render model button clicked
// get the object coordinates from db to use for model and print
var startingXupdate;
var startingYupdate;
var radiusUpdate;

<!--function updateModel (){-->

        <!--var ajax = new XMLHttpRequest();-->
        <!--ajax.open("GET", "propModel.php", true);-->
        <!--ajax.send();-->

        <!--ajax.onreadystatechange = function() {-->
            <!--if (this.readyState == 4 && this.status == 200) {-->
                <!--//var data = JSON.parse(this.responseText);-->
                <!--var midpoints = JSON.parse(JSON.stringify(this.responseText));-->
                <!--//var data = this.responseText;-->
                <!--console.log(midpoints, "\n");-->
                <!--console.log(typeof midpoints);-->

                <!--startingXupdate = JSON.parse(midpoints).midX;-->
                <!--startingYupdate = JSON.parse(midpoints).midY;-->

                <!--startingXupdate = parseInt(startingX, 10);-->
                <!--startingYupdate = parseInt(startingY, 10);-->

                <!--console.log("circle centre points are: ", startingXupdate, startingYupdate);-->

                <!--var ajax = new XMLHttpRequest();-->

                <!--ajax.open("GET", "data.php", true);-->
                <!--ajax.send();-->

                <!--ajax.onreadystatechange = function() {-->
                    <!--if (this.readyState == 4 && this.status == 200) {-->
                        <!--//var data = JSON.parse(this.responseText);-->
                        <!--var data = JSON.parse(JSON.stringify(this.responseText));-->
                        <!--//var data = this.responseText;-->
                        <!--console.log(data, "\n");-->
                        <!--console.log(typeof data);-->

                        <!--var TopCoord = JSON.parse(data).TopCoord;-->
                        <!--TopCoord = TopCoord.split(',');-->

                        <!--var RightCoord = JSON.parse(data).RightCoord;-->
                        <!--RightCoord = RightCoord.split(',');-->

                        <!--var BottomCoord = JSON.parse(data).BottomCoord;-->
                        <!--BottomCoord = BottomCoord.split(',');-->

                        <!--var LeftCoord = JSON.parse(data).LeftCoord;-->
                        <!--LeftCoord = LeftCoord.split(',');-->

                        <!--radiusUpdate = ((parseInt(RightCoord[0], 10) - parseInt(LeftCoord[0], 10)) / 2);-->

                        <!--console.log("radiusUpdate is: ", radiusUpdate);-->

                        <!--//context.globalAlpha = 0.4;-->
                        <!--//context.fillStyle = "#FFFFFF";-->

                        <!--var updateWidth = (parseInt(RightCoord[0], 10) - parseInt(LeftCoord[0], 10));-->
                        <!--var updateHeight = (parseInt(BottomCoord[1], 10) - parseInt(TopCoord[1], 10));-->
                        <!--//context.fillRect(parseInt(LeftCoord[0], 10), parseInt(TopCoord[1], 10), updateWidth, updateHeight);-->


                        <!--context.beginPath();-->
                        <!--context.arc(startingXupdate, startingYupdate, radiusUpdate, 0, 2 * Math.PI, false);-->
                        <!--context.fillStyle = 'white';-->
                        <!--context.fill();-->
                        <!--context.lineWidth = 5;-->
                        <!--context.strokeStyle = '#003300';-->
                        <!--context.stroke();-->

                    <!--}-->
                <!--}-->


            <!--}-->

        <!--}-->
<!--}-->





// get antenna location coordinates from db and render coverage around it


//draw square from back end on front end

//on button press draw diamond inside circle



function diamondapprox() {
        //grab coordinates from database
        //move to starting point
        //line to end point
        //draw


        var ajax = new XMLHttpRequest();
        ajax.open("GET", "data.php", true);
        ajax.send();

        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //var data = JSON.parse(this.responseText);
                var data = JSON.parse(JSON.stringify(this.responseText));
                //var data = this.responseText;
                console.log(data, "\n");
                console.log(typeof data);

                var TopCoord = JSON.parse(data).TopCoord;
                TopCoord = TopCoord.split(',');

                var RightCoord = JSON.parse(data).RightCoord;
                RightCoord = RightCoord.split(',');

                var BottomCoord = JSON.parse(data).BottomCoord;
                BottomCoord = BottomCoord.split(',');

                var LeftCoord = JSON.parse(data).LeftCoord;
                LeftCoord = LeftCoord.split(',');


                context = canvas.getContext("2d");
                context.beginPath();

                context.moveTo(parseInt(TopCoord[0], 10), parseInt(TopCoord[1], 10));
                context.lineTo(parseInt(RightCoord[0], 10), parseInt(RightCoord[1], 10));
                //context.stroke();


                context.moveTo(parseInt(RightCoord[0], 10), parseInt(RightCoord[1], 10));
                context.lineTo(parseInt(BottomCoord[0], 10), parseInt(BottomCoord[1], 10));
                //context.stroke();


                context.moveTo(parseInt(BottomCoord[0], 10), parseInt(BottomCoord[1], 10));
                context.lineTo(parseInt(LeftCoord[0], 10), parseInt(LeftCoord[1], 10));
                //context.stroke();


                context.moveTo(parseInt(LeftCoord[0], 10), parseInt(LeftCoord[1], 10));
                context.lineTo(parseInt(TopCoord[0], 10), parseInt(TopCoord[1], 10));
                context.stroke();


                console.log("DONE!!!");

                //convert the coord strings into numbers
                //use them to draw diamond on front end using diamondapprox



            }
        }

        ajax.addEventListener('error', function( event ) {
                    alert( 'Cannot connect to database!' );
                    });
        return;
}


// function (done by place image on canvas button) that takes in the width and height of the floor-plan
//      DECIDE CENTRE POINTS FOR STARTING AND ENDING CIRCLE
//this will be where the user draws the antenna

//      DECIDE RADIUS OF STARTING AND ENDING CIRCLE
//this will be half the length of the largest dimension (width or height)


var planWidth;
var planlength;

var startingX;
var startingY;

var radius;

var pixelRadius;

var promptCheck;
function PropagationModel (){
    console.log("propagationModel activated");
    //if image/floorplan not been added then prompt user to add one
    if (imageSet != true) {
        console.log("stopped at 1");
        var reset = alert("You must first upload image");

        return;

    }

    console.log("got to 2");
    //if floorplan image has been added then ask for dimensions and feed into model
    if (listChecker >= 1){
                console.log("got to 3");
                var checker = 0;
                while (checker == 0){
                console.log("got to 4");
                    var floorplan = prompt("Enter floor plan dimensions (in metres), e.g. 5, 10", "width, length");

                    if (floorplan == "null" || floorplan == null || floorplan == "" ){
                        console.log("cancel button was pressed");
                        promptCheck = false;
                        return;
                    }

                    if (floorplan != null) {

                          var planDimensions = floorplan.split(',');

                          planDimensions[0] = Math.round(parseInt(planDimensions[0], 10));
                          planDimensions[1] = Math.round(parseInt(planDimensions[1], 10));

                          if (Number.isInteger(planDimensions[0]) && Number.isInteger(planDimensions[1])){

                              checker = 1;
                              planWidth = parseInt(planDimensions[0], 10);
                              planlength = parseInt(planDimensions[1], 10);
                              console.log("dimensions are: " + planWidth, "m ", "BY ", planlength, "m", "\n");

                          }

                          else{
                              continue;
                          }

                    }
                    break;
                }

                drawImageScaled(img, context);

                if (planWidth > planlength){
                    //change back to /2 if needed
                    radius = planWidth;
                    pixelRadius = imageWidth;
                    console.log("pixelRadius 1 is: ", pixelRadius);
                    console.log("imagewidth is: ", imageWidth);

                }

                else{
                //change back to /2 if needed
                radius = planlength;
                pixelRadius = imageHeight;
                console.log("pixelRadius 2 is: ", pixelRadius);
                }

        console.log("radius is: ", radius, "and width is: ", planWidth, "and length is: ", planlength, "\n");




    }

    else{
    console.log("stopped at this else");
    var retry = alert("You must first Select File, then Place it on canvas, then Draw antenna location before Creating Propagation Model");
    }


    promptCheck = true;
    return;
}


//use HEX tint of red from dark red to white, should be 11 of them (array)
// let red = 100%, white = 0%, everything in between is a percentage, i.e. next colour after white is (1/11)*100

let colours = new Map();
//1/1^2
colours.set('1', "#d10d2f");

//1/10^2
colours.set('0.01', "#d52342");
colours.set('0.0025', "#f19414");
colours.set('0.001', "#f1ab14");
colours.set('0.0006', "#ffff00");
colours.set('0.0004', "#ffff7f");
colours.set('0.0003', "#00ff00");
<!--colours.set('0.0002', "#7fff7f");-->
colours.set('0.0002', "#0000ff");
<!--colours.set('0.0001', "#7f7fff");-->
colours.set('0.0001', "#5C246E");


var colourPlot = [];

var colourStops = [];

function precise(x) {
  return Number.parseFloat(x).toPrecision(1);
}

var fieldStrength;


function drawPropModel (){
    if (listChecker >= 3){

        var ajax = new XMLHttpRequest();
        ajax.open("GET", "propModel.php", true);
        ajax.send();

        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                var StrippedString = data.replace(/(<([^>]+)>)/ig,"");
                var midpoints = JSON.parse(JSON.stringify(StrippedString));
                //var data = this.responseText;
                console.log(midpoints, "\n");
                console.log(typeof midpoints);

                startingX = JSON.parse(midpoints).midX;
                startingY = JSON.parse(midpoints).midY;

                startingX = parseInt(startingX, 10);
                startingY = parseInt(startingY, 10);

                console.log("circle centre points are: ", startingX, startingY);





                for (var i = 1; i <= radius; i++){
                        console.log(i, "m done")
                        //drop of 1/r^2 every metre
                        //round to 1 significant figure
                        fieldStrength = precise(1/Math.pow(i, 2));
                        console.log("field strength = 1/r^2 where r is: ", i);
                        console.log("actual field strength is: ", fieldStrength);



                        // roll through dictionary, if rounded field strength...
                        //...is smaller than i but bigger than i+1, then it must be rounded
                        if (fieldStrength <= 1 && fieldStrength > 0.01){
                            var absDiff1 = Math.abs(fieldStrength - 1);
                            var absDiff2 = Math.abs(fieldStrength - 0.01);


                            //...to one of the two numbers. Check distance between the field strength and i
                            //and the distance between the field strength and i+1.
                            //whichever has the shorter distance, will be used as the approximating field strength ratio
                            //and accompanying colour

                            if (fieldStrength == 1){
                                fieldStrength = 1;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            //if closer to 1 than 0.01
                            //place in new array of colours to loop through for model
                            else if (absDiff1 < absDiff2){
                                fieldStrength = 1;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            //round up if in middle
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 1;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.01;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));

                            }

                        }

                        else if (fieldStrength <= 0.01 && fieldStrength > 0.0025){
                            var absDiff1 = Math.abs(fieldStrength - 0.01);
                            var absDiff2 = Math.abs(fieldStrength - 0.0025);


                            if (fieldStrength == 0.01){
                                fieldStrength = 0.01;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.01;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.01;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0025;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }


                        else if (fieldStrength <= 0.0025 && fieldStrength > 0.001){
                            var absDiff1 = Math.abs(fieldStrength - 0.0025);
                            var absDiff2 = Math.abs(fieldStrength - 0.001);




                            if (fieldStrength == 0.0025){
                                fieldStrength = 0.0025;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.0025;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.0025;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.001;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }

                        else if (fieldStrength <= 0.001 && fieldStrength > 0.0006){
                            var absDiff1 = Math.abs(fieldStrength - 0.001);
                            var absDiff2 = Math.abs(fieldStrength - 0.0006);


                            if (fieldStrength == 0.001){
                                fieldStrength = 0.001;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.001;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.001;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0006;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }

                        else if (fieldStrength <= 0.0006 && fieldStrength > 0.0004){
                            var absDiff1 = Math.abs(fieldStrength - 0.0006);
                            var absDiff2 = Math.abs(fieldStrength - 0.0004);

                            if (fieldStrength == 0.0006){
                                fieldStrength = 0.0006;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }


                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.0006;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.0006;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0004;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }

                        else if (fieldStrength <= 0.0004 && fieldStrength > 0.0003){
                            var absDiff1 = Math.abs(fieldStrength - 0.0004);
                            var absDiff2 = Math.abs(fieldStrength - 0.0003);


                            if (fieldStrength == 0.0004){
                                fieldStrength = 0.0004;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.0004;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.0004;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0003;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }

                        else if (fieldStrength <= 0.0003 && fieldStrength > 0.0002){
                            var absDiff1 = Math.abs(fieldStrength - 0.0003);
                            var absDiff2 = Math.abs(fieldStrength - 0.0002);


                            if (fieldStrength == 0.0003){
                                fieldStrength = 0.0003;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.0003;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.0003;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0002;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }

                        else if (fieldStrength <= 0.0002 && fieldStrength > 0.0001){
                            var absDiff1 = Math.abs(fieldStrength - 0.0002);
                            var absDiff2 = Math.abs(fieldStrength - 0.0001);


                            if (fieldStrength == 0.0002){
                                fieldStrength = 0.0002;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else if (absDiff1 < absDiff2){
                                fieldStrength = 0.0002;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }
                            else if (absDiff1 = absDiff2){
                                fieldStrength = 0.0002;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                            else{
                                fieldStrength = 0.0001;
                                colourPlot.push(colours.get(fieldStrength.toString()));
                                console.log("field strength is: ", fieldStrength);
                                console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                            }

                        }



                        else{
                            fieldStrength = 0.0001;
                            colourPlot.push(colours.get(fieldStrength.toString()));
                            console.log("field strength is: ", fieldStrength);
                            console.log("colour chosen is : ", colours.get(fieldStrength.toString()));
                        }

                        //console.log("colour is: ", colours.get(fieldStrength.toString()));
                        //console.log("colour is: ", colours.get(fieldStrength.toString()));

                }//end of for loop

                console.log("finally got here!!")
                //continue propagation model
                //array for colour stops
                //over span of room distance, mark out fraction of total distance
                for (var i = 1; i <= radius; i++){
                    colourStops.push(i/radius);
                }

                //radius in pixel terms is radius of pic in pixel terms/radius in metres x 1 for 1metre
                //check if width or
                console.log("startingX is: ", startingX, " startingY is: ", startingY);
                console.log("radius is: ", radius, " pixelRadius is: ", pixelRadius);
                var grd = context.createRadialGradient(Math.round(startingX), Math.round(startingY), Math.round(pixelRadius/radius), Math.round(startingX), Math.round(startingY), Math.round(pixelRadius));



                for (var i = 1; i < colourPlot.length; i++){
                    if (colourPlot[i] != colourPlot[i-1]){
                        console.log(i, colourStops[i], colourPlot[i])
                        grd.addColorStop(colourStops[i], colourPlot[i]);
                    }
                    else{
                        continue;
                    }

                }
                context.globalAlpha = 0.6;
                context.fillStyle = grd;
                console.log(centerShift_x, centerShift_y, width_ratio, height_ratio);
                context.fillRect(centerShift_x, centerShift_y, width_ratio, height_ratio);
            }

        }

        ajax.addEventListener('error', function( event ) {
                    alert( 'Cannot connect to database!' );
                    });

        return;

    }

    else{
        var sendBack = alert("First draw antenna position");
        return;
    }



}





//use javascript createRadialGradient()
//use javascript addColorStop()
//      DECIDE COLOUR OF EACH COLOUR STOP...FOR FIELD STRENGTH



//colour in radialGradient model is determined by 1/r^2 model percentage of original
//i.e. find field strength at each metre from source, as a percentage of the orginal, using 1/r^2
//don't need to know original field strength to know this
//match this percentage to a colour with the closest percentage to this percentage



//      DECIDE POSITION OF EACH COLOUR STOP (VALUE BETWEEN 0 AND 1), equal distances
//position in radialGradient model is every 1m ending at max width or max length of floor plan, whichever is larger, e.g. width
//e.g if width is 20m an length is 10m, then do 20 colour stops... first colour stop is...
//...1/20, second is 2/20 etc
//then create n colour stops, (n being the number of actual metres in the floor plan largest dimension)

//then use rectangle pixel dimensions of floor plan to place on canvas inside the floor-plan


//============================================================================================

//make slightly opaque to see floor plan
//add in removed object locations
//if there is time, add a key showing field strengths as percentage of original, and corresponding colour


//deploy online
</script>



</body>
</html>
