is_loaded = 0;

function id(v){return document.getElementById(v); }

function doneLoading(){
    is_loaded = 1;
    ovrl = id("overlay");
    ovrl.style.opacity = 0;
    setTimeout(function(){ 
        ovrl.style.display = "none";
    }, 1200);
}

function update_loading(n)
{
    stat = id("progstat");
    if(n == 1){
        x = ".";
    }

    if(n == 2){
        x = ". .";
    }

    if(n == 3){
        x = ". . .";
    }

    stat.innerHTML = "Loading " + x;
}

function loading_updater(n)
{
    if(n + 1 == 4)
    {
        n = 0;
    }
    n = n + 1;

    update_loading(n);

    if(!is_loaded){
        setTimeout(function(){
            loading_updater(n);
        }, 500);
    }
}

(function(){
  function loadbar() {
    var ovrl = id("overlay"),
        stat = id("progstat");

      var perc = "100%";
    loading_updater(0);
  }

  loadbar();
}());

