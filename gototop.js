let calcScrollValue = () => {
    let scollprogress = document.getElementById("progress");
    let progressvalue = document.getElementById("up-icon");
    let pos = document.documentElement.scrollTop;
    let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrollvalue = Math.round((pos * 100)/calcHeight);
    
    if(pos>130){
        scollprogress.style.display = "grid";
    }
    else{
        scollprogress.style.display = "none";
    }
    scollprogress.addEventListener("click", () => {
        document.documentElement.scrollTop = 0;
    });
    scollprogress.style.background = `conic-gradient(#E2857B ${scrollvalue}%, #d7d7d7 ${scrollvalue}%)`;
};

window.onscroll = calcScrollValue;
window.onload = calcScrollValue;