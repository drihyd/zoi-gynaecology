 var current_fs, next_fs, previous_fs; //fieldsets
         var left, opacity, scale; //fieldset properties which we will animate
         var animating; //flag to prevent quick multi-click glitches
         
         $(".next").click(function(){
         if(animating) return false;
         animating = true;
         
         current_fs = $(this).parent();
         next_fs = $(this).parent().next();
         
         //activate next step on progressbar using the index of next_fs
         $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
         
         //show the next fieldset
         next_fs.show(); 
         //hide the current fieldset with style
         current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                // scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = 0;
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                width = 100+"%"
                current_fs.css({
                // 'transform': 'scale('+scale+')',
                'position': 'absolute',
                'width':width,
              });
                next_fs.css({'left': left, 'opacity': opacity});
            }, 
            // duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
         });
         });

         
         
         $(".previous").click(function(){
         if(animating) return false;
         animating = true;
         
         current_fs = $(this).parent();
         previous_fs = $(this).parent().prev();
        
         //de-activate current step on progressbar
         $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
         
         //show the previous fieldset
         previous_fs.show(); 
         //hide the current fieldset with style
         current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                // scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = 0;
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                width = 100+"%"
                current_fs.css({'left': left , 'width':width});
                previous_fs.css({ 'opacity': opacity});
            }, 
            // duration: 800, 
            complete: function(){
                current_fs.hide();
                animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
         });
         });
         
         $(".submit").click(function(){
         return false;
         })

       


function package_1() {
  var x = document.getElementById("p_1").value;
  document.getElementById("package_namee").innerHTML = x;
}
function package_2() {
  var x = document.getElementById("p_2").value;
  document.getElementById("package_namee").innerHTML = x;
}
function package_3() {
  var x = document.getElementById("p_3").value;
  document.getElementById("package_namee").innerHTML = x;
}
function package_4() {
  var x = document.getElementById("p_4").value;
  document.getElementById("package_namee").innerHTML = x;
}
function package_5() {
  var x = document.getElementById("p_5").value;
  document.getElementById("package_namee").innerHTML = x;
}

function room_1() {
  var y = document.getElementById("r_1").value;
  document.getElementById("room_select").innerHTML = y;
}
function room_2() {
  var y = document.getElementById("r_2").value;
  document.getElementById("room_select").innerHTML = y;
}
function room_3() {
  var y = document.getElementById("r_3").value;
  document.getElementById("room_select").innerHTML = y;
}
function room_4() {
  var y = document.getElementById("r_4").value;
  document.getElementById("room_select").innerHTML = y;
}



