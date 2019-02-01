

$(document).ready(function() {

    // Détermination de la position du center
    let width=$(document).width();
    let height=$(document).height();
    let xcenter = width/2;
    let ycenter = height/2*0.77;
    
    // Détermination du rayon du cercle
    let r=height/2*0.57;
    
    // Détermination de la largeur d'une vignette
    let vignetteWidth=document.getElementById('vignette1').clientWidth;
    
    // Positionnement initial des vignettes
    positionVignettes();
    
    // Positionnement du titre
    let title=document.getElementById('mainTitle');
    let titleWidth=document.getElementById('mainTitle').clientWidth;
    title.style.top=100*(ycenter)/height+"%";
    title.style.left=100*(xcenter-titleWidth/2)/width+"%";
    
    // Apparition du titre
    $(title).animate({opacity: '1'}, 2500, 'linear', function(){});
    
    // Positionnement intitial des nuages
    $("#cloud1").css('left',0.1*width);
    $("#cloud2").css('left',0.5*width);

    // Si changement de taille de fenêtre /////////////////////////////////////////////////////////////////////////////////////////
    $(window).resize(function() {
        width = $(document).width();
        height = $(document).height();
        xcenter = width/2;
        ycenter = height/2*0.78;
        r=height/2*0.6;
        $(".mainRow").css('height',$(window).height()+"px");
        positionVignettes();
        title.style.top=100*(ycenter)/height+"%";
        title.style.left=100*(xcenter-titleWidth/2)/width+"%";
    });
    
    
    // Mouvement des nuages ///////////////////////////////////////////////////////////////////////////////////////////////////////
    let sens1=2;
    let sens2=1;
    let cloud1=document.getElementById("cloud1");
    let cloud2=document.getElementById("cloud2");
    cloud1.style.top = "30px";
    cloud2.style.top = "220px";
    let largeurCloud1 = cloud1.offsetWidth/10;
    let largeurCloud2 = cloud2.offsetWidth/10;
    let xCloud1=parseFloat(getComputedStyle(cloud1).left);
    let xCloud2=parseFloat(getComputedStyle(cloud2).left);

    function deplacerCloud(id, position,deplacement){
        id.style.left = (position + deplacement)+"px";
    };

    function testerPosition(x, largeur, sens, width){
        if(x+largeur+10*sens>width){
            x=-2*largeur
        }
        return x
    };

    setInterval(function() {
        xCloud1=testerPosition(xCloud1, largeurCloud1, sens1, width);
        xCloud2=testerPosition(xCloud2, largeurCloud2, sens2, width);
        deplacerCloud(cloud1,xCloud1,sens1);
        deplacerCloud(cloud2,xCloud2,sens2);
        xCloud1=parseFloat(getComputedStyle(cloud1).left);
        xCloud2=parseFloat(getComputedStyle(cloud2).left);
    }, 20);
    

    // FadeIn des vignettes ///////////////////////////////////////////////////////////////////////////////////////////////////////
    let delay=4000;
    $('#vignette-item1').animate({opacity: '1'}, delay, 'linear', function(){});
    $('#vignette-item2').animate({opacity: '1'}, delay, 'linear', function(){});
    $('#vignette-item3').animate({opacity: '1'}, delay, 'linear', function(){});
    $('#vignette-item4').animate({opacity: '1'}, delay, 'linear', function(){});
    $('#vignette-item5').animate({opacity: '1'}, delay, 'linear', function(){});
    $('#vignette-item6').animate({opacity: '1'}, delay, 'linear', function(){});
    $('.name').animate({opacity: '0.85'}, delay, 'linear', function(){});



    // Mouvement circulaire //////////////////////////////////////////////////////////////////////////////////////////////////////
    let max=2;
    for(let j=0;j<max;j++){
        for(let i=0;i<=100;i++){
            moveitcircular('vignette1',2*Math.PI*i/100-Math.PI/2);
            moveitcircular('vignette2',2*Math.PI*i/100-Math.PI/6);
            moveitcircular('vignette3',2*Math.PI*i/100+Math.PI/6);
            moveitcircular('vignette4',2*Math.PI*i/100+Math.PI/2);
            moveitcircular('vignette5',2*Math.PI*i/100+7*Math.PI/6);
            moveitcircular('vignette6',2*Math.PI*i/100+5*Math.PI/6);
        };
    }

    // Détermination de la position des vignettes
    function positionVignettes(){
        let vignette1=document.getElementById('vignette1');
        vignette1.style.top=drawing(Math.PI/2)[0];
        vignette1.style.left=drawing(Math.PI/2)[1];

        let vignette2=document.getElementById('vignette2');
        vignette2.style.top=drawing(Math.PI/6)[0];
        vignette2.style.left=drawing(Math.PI/6)[1];

        let vignette3=document.getElementById('vignette3');
        vignette3.style.top=drawing(-Math.PI/6)[0];
        vignette3.style.left=drawing(-Math.PI/6)[1];

        let vignette4=document.getElementById('vignette4');
        vignette4.style.top=drawing(-Math.PI/2)[0];
        vignette4.style.left=drawing(-Math.PI/2)[1];

        let vignette5=document.getElementById('vignette5');
        vignette5.style.top=drawing(5*Math.PI/6)[0];
        vignette5.style.left=drawing(5*Math.PI/6)[1];

        let vignette6=document.getElementById('vignette6');
        vignette6.style.top=drawing(7*Math.PI/6)[0];
        vignette6.style.left=drawing(7*Math.PI/6)[1];
    }

    function drawing(teta){
        let top=Math.floor(ycenter-r*Math.sin(teta))*100/height+"%";
        let left=Math.floor(xcenter-vignetteWidth/2+r*Math.cos(teta))*100/width+"%";
        return [top, left];
    }

    // Déclenchement du son onclick /////////////////////////////////////////////////////////////////////////////////////////////
    var audioElement = $("#tir")[0];
    for(let i=1;i<=6;i++){
        let vignetteId='#vignette-item'+i;
        $(vignetteId).click(function(){ 
            audioElement.play();
        });
    }

    // Sepia filter on hover ///////////////////////////////////////////////////////////////////////////////////////////////////
    function changeOnHover (i) {
        let selectedVignette = $("#vignette-item" + i);
        let vignetteArray = [1, 2 , 3, 4, 5 , 6];
        let deleteArray = vignetteArray.splice(i - 1, 1);
        selectedVignette.hover(function() {
            for (let j = 0; j < 6; j++) {
                $("#vignette-item" + vignetteArray[j]).css('filter','sepia\(100%\)');
            }
        },function() {
            for (let j = 0; j < 6; j++) {
                $("#vignette-item" + vignetteArray[j]).css('filter','none');
            }
        })
    }
    
    for (let i = 1; i < 7; i++) {
        changeOnHover(i);
    }
    
    // Remplissage aléatoire des vignettes (photo, target et titre)
    //Données d'entrée
    let wilders=["stephane", "zakaria", "hugo", "mathieu", "magali", "marion"];
    let wildersFullName=["Stéphane Guinot", "Zakaria Hamichi", "Hugo Hontans", "Mathieu Kanel", "Magali Klein", "Marion Koosinlin"];
    let wildersFirstName=["Stéphane", "Zakaria", "Hugo", "Mathieu", "Magali", "Marion"];
    let wildersLastName=["Guinot", "Hamichi", "Hontans", "Kanel", "Klein", "Koosinlin"];
    let wildersLinkedinLinks=["https://www.linkedin.com/in/stephane-guinot-47a6aa97/","https://www.linkedin.com/in/zakaria-hamichi-ab8893170/","https://www.linkedin.com/in/hugo-hontans-34b798170/","https://www.linkedin.com/in/mathieu-kanel-64233b160/","https://www.linkedin.com/in/klein-magali/?originalSubdomain=fr","https://www.linkedin.com/in/marion-koo-sin-lin-150161114/"]
    let wildersGithubLinks=["https://github.com/StephaneGNT","https://github.com/ZakariaHamichi","https://github.com/Hugo-Hontans","https://github.com/Nexter73","https://github.com/Pelican07","https://github.com/Yumenotsuki"]
    let j=1;
    while(wilders.length>0){
        // "Choice" of random wilder
        let i=Math.round(Math.random()*(wilders.length-1));
        let bgImage="url('/assets/images/groupe5/"+wilders[i]+".jpg')";
        let bgImageHover="url('/assets/images/groupe5/"+wilders[i]+"-chapeau.png')";
        // Ecran d'accueil ordinateur
        // Selection of vignette
        let idvignette="#vignette-item"+j;
        // Selection of background-image
        $(idvignette).css('background-image',bgImage);
        // Selection of background-image on hover
        $(idvignette).hover(function(){
            $(this).css('background-image',bgImageHover);
        },function() {
            $( this ).css('background-image',bgImage);
          }
        );
        // Selection of data target
        let dataTarget="#modal"+wilders[i];
        $(idvignette).attr('data-target',dataTarget);
        
        // Selection of name
        let titre="#titreVignette"+j;
        let wilderName="";
        wilderName=wildersFirstName[i]+"<br>"+wildersLastName[i]
        $(titre).html(wilderName);

        
        // Ecran d'accueil mobile
        let mobileImage="/assets/images/groupe5/"+wilders[i]+"-chapeau.png";
        let idbutton="#button"+j;
        let idbuttonimg=idbutton+" img";
        $(idbuttonimg).attr('src',mobileImage);
        let idbuttontitre=idbutton+" span";
        $(idbuttontitre).text(wildersFullName[i]);
        let idObject="#object"+j;
        $(idObject).attr('data',"/assets/page/groupe5/content"+wilders[i]+".html");
        let linkedInId="#linkedinLogo"+j;
        $(linkedInId).attr('href',wildersLinkedinLinks[i]);
        let githubId="#gitHubLogo"+j;
        $(githubId).attr('href',wildersGithubLinks[i]);
        
        // Removal of chosen wilder
        wilders.splice(i,1);
        wildersFullName.splice(i,1);
        wildersFirstName.splice(i,1);
        wildersLastName.splice(i,1);
        wildersLinkedinLinks.splice(i,1);
        wildersGithubLinks.splice(i,1);


        // Incrementation of vignette number
        j++;
    }
    
    // Déplacement circulaire //////////////////////////////////////////////////////////////////////////////////////////////////
    function moveitcircular(id,t) {
        let newLeft = (xcenter - vignetteWidth/2 + (r * 1.05 * Math.cos(t)));
        let newTop = (ycenter + (r*1.05 * Math.sin(t)));
        
        $("#"+id).animate({
            top: newTop,
            left: newLeft,
        }, 1)
    }
});