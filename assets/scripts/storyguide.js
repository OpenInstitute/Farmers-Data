var enjoyhint_instance = new EnjoyHint({});


var enjoyhint_script_steps = [{
        'next .stories': '<div class="guideBox"><div class="guideInnerBox"> <h3>Hi, Wizzy wa Nakuru is not around!</h3> <p> I will help you navigate this site. Click here if you want to see stories generated from the data <br/> Do you need assistance to navigate the site?</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>',
        "nextButton": { className: "myNext", text: "Yes" },
        "skipButton": { className: "mySkip", text: "Thanks!" }
    },
    {
        'next .guideme': '<div class="guideBox"><div class="guideInnerBox"> <h3>Click here to launch this guide!</h3> <p> If you need to have this tour again, just click here and the tour will start </p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>',
        "nextButton": { className: "myNext", text: "Okay..." },
        "skipButton": { className: "mySkip", text: "Skip" }
    },
    {
        'next .typeFilter': '<div class="guideBox"><div class="guideInnerBox"> <h3>Hide/Show Filter!</h3> <p> Use this amazing feature to declutter your screen and give you more space to see the data analysis. Your choices are saved and you can view them any time you want. If you reload the page your choices will be lost.  </p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typeFarm': '<div class="guideBox"><div class="guideInnerBox"> <h3>Type of farming</h3> <p>Use this option to select the type of farming that you need to see displayed</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typeLocation': '<div class="guideBox"><div class="guideInnerBox"> <h3>Choose Location</h3> <p>Use this option to select the location that you need to see displayed</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typeGender': '<div class="guideBox"><div class="guideInnerBox"> <h3>Gender</h3> <p>Select gender to get a narrowed down data filtered by gender</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typeAge': '<div class="guideBox"><div class="guideInnerBox"> <h3>Age</h3> <p>Use this option to sort by age of the farmers, to get insights per age group</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typePractice': '<div class="guideBox"><div class="guideInnerBox"> <h3>Type of Farming</h3> <p>If you need to see whether the farmers practice subsistence or for profit farming, this is the option to select</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>'
    },
    {
        'next .typeHouse': '<div class="guideBox"><div class="guideInnerBox"> <h3>Type of House Structure</h3> <p>Filter against type of house that the respondents live in</p><span class="guideImg"><img src="assets/image/kahiki-farmer.png" alt=""/> </span></div>',
        'showSkip': false,
        'nextButton': {
            className: "myNext",
            text: "Got it!"
        }
    }


];

//set script config
enjoyhint_instance.set(enjoyhint_script_steps);

//run Enjoyhint script
enjoyhint_instance.run();