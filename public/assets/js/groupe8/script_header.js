/* function for the sound */
function playpause(){

let sound = document.getElementById('v1')

if(sound.paused==true){
    sound.play()
    }else{
    sound.pause()
    sound.currentTime=0
    }
    }
