var enjoyhint_instance = new EnjoyHint({});


var enjoyhint_script_steps = [{
        'next .stories': '<div class="guideBox"><div class="guideInnerBox"> <h3>Hi my name is Wizard wa Nakuru!<h3> <p> I am here to help you navigate this site. Click here if you want to see stories generated from the data <br/> Do you need assistance to navigate the site?</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>',
        "nextButton": { className: "myNext", text: "Yes" },
        "skipButton": { className: "mySkip", text: "Thanks!" },
    },
    {
        'next .guideme': '<div class="guideBox"><div class="guideInnerBox"> <h3>Launch Guide<h3> <p>Click on this panel whenever you need to launch guide</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    },
    // {
    //     'next .nnmap': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Heat Map <h3> <p> This is a map of Nakuru North showing a heatmap with different aspects filtered using the buttons below: </p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    // },
    {
        'next .sublocation': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Population by Sublocation <h3> <p> Select this option to generate a heatmap showing the distribution of people based on their location</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    },
    {
        'next .agegroup': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Population by Age Group <h3> <p>When you click on this, you will be able to see the distribution of people by their age group</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    },
    {
        'next .gender': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Population by Gender <h3> <p>If you only want to see a heatmap based on gender, clicking on this button will generate this data.</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    },
    {
        'next .locationData': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Location Data <h3> <p>This is a brief overview of location information</p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    },
    {
        'next .scenarioData': '<div class = "guideBox"> <div class = "guideInnerBox"> <h3> Data Stories by Case Study <h3> <p>This section showcases different scenarios of different age groups in these locations who practice farming as their main economic activities. One represents: <ul><li>the youth aged between 18-40,</li> <li> middle aged population aged between 41-60 and </li><li> the older generation farmers aged 60 years and above</li></ul></p><span class="guideImg"><img src="assets/image/WizzieMzae.png" alt=""/> </span></div>'
    }
];

//set script config
enjoyhint_instance.set(enjoyhint_script_steps);

//run Enjoyhint script
enjoyhint_instance.run();