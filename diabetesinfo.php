<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Diabetes Information</title>

    <style>

    
    /*------------ moving text---------------*/
        .marquee {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    background: linear-gradient(90deg, #7e939bff, #6c7e86ff);
    padding: 12px 0;
    color: white;
    font-size: 30px;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.marquee span {
    display: inline-block;
    padding-left: 100%;
    animation: marqueeMove 28s linear infinite;
}

@keyframes marqueeMove {
    from {
        transform: translateX(0%);
    }
    to {
        transform: translateX(-100%);
    }
}
/* ---------------image----------------*/
.hero-section {
    position: relative;
    width: 100%;
    margin-top: 1%;
}

.hero-section img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    filter: brightness(70%);
}

.hero-text {
    position:absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align:center;
    color: #fff;
}

.hero-text h1 {
    font-size: 60px;
    font-weight: bold;
    animation: fadeIn 2s ease-in-out;
}

.hero-text p {
    font-size: 25px;
    margin-top: 10px;
    animation: fadeIn 3s ease-in-out;
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}
/* ---------------------------------------------- GENERAL DIABETES SECTION ---------------- */
/* MAIN LAYOUT */
.general-diabetes {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px;
    margin: 30px auto;
    border-radius: 15px;
    background: cornsilk;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* TEXT WIDTH */
.general-diabetes-text {
    width: 70%;
}

.general-diabetes-text h2 {
    font-size: 30px;
    margin-bottom: 15px;
}

.general-diabetes-text ul li {
    margin-bottom: 10px;
    font-size: 17px;
    line-height: 1.6;
    color: #333;
}

/* IMAGE */
.small-image {
    width: 30%;
}

.small-image img {
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: float 4s infinite ease-in-out;
}

/* ALTERNATE STRUCTURE (reverse section = image left) */
.reverse {
    flex-direction:row;
    
}

/* ------------------------ SCROLL SLIDE ANIMATION ------------------------ */

/* Hidden state before scroll */
.fade-section {
    opacity: 0;
    transform: translateY(40px);
    transition: all 1s ease;
}

/* Visible state */
.fade-section.show {
    opacity: 1;
    transform: translateY(0);
}

/* Slide From Left */
.slide-left {
    transform: translateX(-80px);
}

.slide-left.show {
    transform: translateX(0);
}

/* Slide From Right */
.slide-right {
    transform: translateX(80px);
}

.slide-right.show {
    transform: translateX(0);
}

/* RESPONSIVE */
@media(max-width: 900px) {
    .general-diabetes {
        flex-direction: column;
    }
    .general-diabetes-text,
    .small-image {
        width: 100%;
    }
}

/*---------------------------------information-----------*/
/* MAIN SPLIT SECTION */
.diabetes-section {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    width: 100%;
    margin-top: 35px;
    position: relative;
    height:auto;
}

/* LEFT + RIGHT PANELS */
.type {
    width: 48%;
    padding: 30px;
    color: white;
    animation: fadeInUp 1.5s ease-in-out;
}

/* LEFT SIDE (TYPE 1) */
.type1 {
    background: linear-gradient(135deg, #345f79ff, #fcd196ff);
    border-radius: 15px 15px 15px 15px;
    animation-delay: 0.2s;
}

/* RIGHT SIDE (TYPE 2) */
.type2 {
    background: linear-gradient(135deg, #345f79ff, #a9eef3ff);
    border-radius: 15px 15px 15px 15px;
    animation-delay: 0.6s;
}

/* HEADINGS */
.type h2 {
    font-size: 28px;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
}

/* TEXT */
.type p {
    font-size: 18px;
    line-height: 1.6;
    font-weight: 400;
    text-align: justify;
    
}

/* MIDDLE DIVIDER LINE */
.divider-line {
    width: 4px;
    background: #fff;
    border-radius: 10px;
    position: absolute;
    left: 50%;
    top: 0;
    height: 100%;
    transform: translateX(-50%);
    animation: growLine 1.2s ease-in-out;
}

/* ANIMATIONS */
@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(25px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes growLine {
    0% { height: 0; }
    100% { height: 100%; }
}

/* RESPONSIVE FOR MOBILE */
@media (max-width: 768px) {
    .diabetes-section {
        flex-direction: column;
        height: auto;
    }

    .divider-line {
        display: none;
    }

    .type {
        width: 100%;
        border-radius: 15px;
        margin-bottom: 20px;
    }
}
.all {
    width: 900px;
    margin: 30px auto;
    display: grid;
    grid-template-columns: repeat(2, 1fr);   /* 2 columns */
    gap: 20px; 
}

.topic-box {
    background:lightsteelblue;
    padding: 18px 22px;
    border-radius: 10px;
    margin-bottom: 15px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    font-size: 25px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
    max-width:900px;
    text-align:center;
}

.topic-box:hover {
    transform: translateY(-3px);
    background: lightpink;
}

/* POPUP BACKDROP */
.popup {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.65);
    display:none;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.4s ease;
    z-index: 999;
}

/* POPUP CONTAINER*/ 
.popup-content {
    background: white;
    width: 70%;
    max-height: 80%;
    overflow-y: auto;
    border-radius: 15px;
    padding: 20px 30px;
    box-shadow: 0 4px 20px rgba(100, 108, 216, 0.3);
    animation: slideUp 0.4s ease;
    text-align: justify;
    
}

/* CLOSE BUTTON */
.close {
    float: right;
    font-size: 32px;
    cursor: pointer;
    color: #444;
}



/* ANIMATIONS */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 columns */
    gap: 20px; /* space between boxes */
    width: 80%;
    margin: 30px auto;
}

.img-card {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
}

.img-card img {
    width: 100%;
    height: 200px; 
    object-fit: cover; 
    border-radius: 10px;
}

.caption {
    margin-top: 10px;
    font-size: 16px;
    font-weight: 500;
}
</style>
</head>

<body>

<div class="marquee">
    <span>Welcome to Diabetic Care and Dietary Management System for Type-1</span>
</div>

<div class="hero-section">
    <img src="img/dia.jpg" alt="Diabetes Image">
    <div class="hero-text">
        <h1>Healthy Choices Begin With Awareness</h1>
        <p>Your journey towards better health starts here</p>
        <p>Know Your Diabetes — Take Control of Your Health</p>
    </div>
</div>

<!------------------ GENERAL INFO SECTION ------------------->
<h1 style="text-align: center; color:green;">General Information About Diabetes</h1>

<section class="general-diabetes fade-section slide-left">
    <div class="general-diabetes-text">
        <h2>What is Diabetes?</h2>
        <ul>
            <li>Diabetes is a chronic condition that affects how the body converts food into energy.</li>
            <li>Food turns into glucose and enters the bloodstream.</li>
            <li>Insulin helps glucose enter the cells.</li>
            <li>In diabetes, insulin is not produced or used properly.</li>
            <li>High blood sugar damages body organs.</li>
        </ul>
    </div>

    <div class="small-image">
        <img src="img/dia1.jpg" alt="Diabetes Awareness">
    </div>
</section>


<section class="general-diabetes reverse fade-section slide-right">
    <div class="small-image">
        <img src="img/pan.jpg" alt="Diabetes Awareness">
    </div>

    <div class="general-diabetes-text">
        <h2>Why Does Diabetes Happen?</h2>
        <ul>
            <li>The pancreas fails to produce enough insulin, which leads to Type 1 diabetes.</li>
            <li>In Type 2 diabetes, the body becomes resistant to insulin, making it difficult for cells to use glucose properly.</li>
            <li>A strong family history or genetic factors can significantly increase the risk of developing diabetes.</li>
            <li>Obesity along with unhealthy eating habits greatly contributes to higher blood sugar levels.</li>
            <li>A lack of regular physical activity reduces the body’s ability to manage glucose effectively.</li>
            <li>High stress levels and an unhealthy lifestyle over time can weaken the pancreas and affect insulin production.</li>
        </ul>
    </div>
</section>

<section class="general-diabetes fade-section slide-left">
    <div class="general-diabetes-text">
        <h2>Common Symptoms of Diabetes</h2>
        <ul>
            <li><b>Increased thirst and frequent urination:</b> Feeling constantly thirsty and needing to pee more often, especially at night.</li>
            <li><b>Hunger & Fatigue:</b> Feeling very hungry (even after eating) and unusually tired because your body isn't getting energy from food.</li>
            <li>Sudden weight loss, Extreme tiredness, Blurred vision.</li>
            <li>Slow healing of cuts or wounds, Dry, itchy skin, or dark, velvety patches (acanthosis nigricans).</li>
            <li>Numbness or tingling in hands and feet.</li>
        </ul>
    </div>
    <div class="small-image">
        <img src="img/symptoms.jpg" alt="Diabetes Awareness">
    </div>
</section>

<section class="general-diabetes reverse fade-section slide-right">
    <div class="small-image">
        <img src="img/test.jpg" alt="Diabetes Awareness">
    </div>

    <div class="general-diabetes-text">
        <h2>How Diabetes Is Diagnosed?</h2>
        <p>Doctors diagnose diabetes using blood tests that measure sugar levels.
                Main diagnostic tests:</p>
        <ul>
            <li><b>Fasting Blood Sugar Test:</b> Sugar level checked after 8 hours of fasting.</li>
            <li><b>Random Blood Sugar Test:</b> Sugar checked anytime.</li>
            <li><b>HbA1c Test:</b> Shows average blood sugar from last 3 months.</li>
            <li><b>Oral Glucose Tolerance Test:</b> Sugar checked before and after a glucose drink.</li>
        </ul>
    </div>
</section>

<section class="diabetes-section">
    <div class="type type1">
        <h2>Type 1 Diabetes</h2>
        <p>
            This type is an autoimmune disease in which your immune 
            system attacks and destroys insulin-producing cells in your 
            pancreas for unknown reasons. Up to 10% of people who have diabetes have 
            Type 1. It’s usually diagnosed in children and young adults, but it can develop at any age.
        </p>
    </div>

    <div class="divider-line"></div>

    <div class="type type2">
        <h2>Type 2 Diabetes</h2>
        <p>
            With this type, your body doesn’t make enough 
            insulin and/or your body’s cells don’t respond normally 
            to the insulin (insulin resistance). This is the most 
            common type of diabetes. It mainly affects adults, but children can have it as well.
        </p>
    </div>
</section>

<!-- MAIN SECTION LIST -->
 <h1 style="text-align: center;color:palevioletred">Your Friendly & Complete Guide to Learn More About Diabetes</h1>
<div class="all">

    <!-- 1. What is Diabetes? -->
    <div class="topic-box" data-popup="popup1"> What is Diabetes?</div>
    <!-- POPUP 1 -->
    <div class="popup" id="popup1">
        <div class="popup-content">
            <span class="close" data-close>&times;</span>

            <h2>What is Diabetes?</h2>
            <p style="width: 65%;">
                When you have diabetes, your body does not make enough insulin or does not use insulin well.  
                Every cell in the body needs glucose for energy. Glucose comes from the food you eat.  
                Insulin works as a key that allows glucose to enter the cells.  
                Insulin is made in your pancreas.
            </p>
           <div style="margin-left: 600px;margin-top:-130px;" ><img width="230px" height="120px" src="img/dia2.jpg"></div>
              
           <h2>What kind of diabetes do I have?</h2> 
              <p>There are different kinds of diabetes that children can have.</p> 
                <p>The most common are:</p>
                <ul><li>Type 1 diabetes</li><li>Type 2 diabetes</li></ul>
                <p>You can also have both types of diabetes. Sometimes your doctor will know 
                  right away what type of diabetes you have. Sometimes your doctor will need 
                  to see the results of lab tests before they know which type of diabetes you 
                  have. This can take many weeks. </p>
                  <h3><u>Type 1 diabetes</u></h3>
                  <ul style="width: 60%;"><li>The most common type of diabetes in children, teens, and young adults.</li>
                       <li>An autoimmune disorder.This means that the body gets rid of the cells that make insulin.</li>
                       <li>The body has little or no insulin.</li>
                       <li>You will always need to take insulin.</li>
                </ul>
                    <div style="margin-left:600px;margin-top:-140px;" ><img width="230px" height="120px" src="img/type1.jpg"></div>
                <h3><u>Type 2 diabetes</u></h3>
                  <ul style="width: 100%;"><li>It is more common in adults. Some children and teens can develop Type 2 diabetes.</li>
                       <li>Insulin is in the body, but the body cannot use it well. This is called ‘insulin resistance.’</li>
                       <li>It may be controlled by eating healthy foods, exercising every day, and being at a healthy weight.</li>
                       <li>Sometimes you will need pills to help your body use insulin better or get rid of extra glucose in the body.</li>
                </ul>
 
                 <h2>What happens when I have diabetes?</h2>
                 <p>When there is not enough insulin or insulin does not work well:</p>
                 <ul><li>The glucose stays in the blood.</li>
                    <li>When the blood glucose gets too high, some glucose leaves the body through the urine.</li>
                    <li>Water leaves the body with the glucose.</li>
                </ul>
                 <h2> What do I need to do if I have diabetes?</h2>
                 <p> Keep your blood glucose as close to normal as you can. A normal blood glucose is 70 to 120 mg/dl.</p>
                 <h2> How do I keep my blood glucose in a normal range?</h2>
                 <p> You can learn to manage your diabetes by learning to think like a pancreas.
                    When your pancreas is working well it gives your body the amount of insulin 
                    you need for the food you eat and your activity level at that time.
                     This keeps your blood glucose in a normal range.
                     <h3><u>Think and act like a pancreas by:</u></h3>
                     <ul>
                        <li>Checking your blood glucose at least 4 times each day.</li>
                        <li>Taking insulin at least 4 times each day.</li>
                        <li>Counting carbohydrates in the foods you eat.</li>
                        <li>Learning what makes your blood glucose change and making changes to 
                            keep your blood glucose in target range</li>
                    </ul>
                    <h2>Why do I need to take care of diabetes?</h2>
                    <p>There are health problems that can happen when your blood glucose is too low or too high. We will teach you what to do when this happens. 
                    Having diabetes does not cause permanent problems, but high blood 
                       glucose over many years can cause permanent problems.  </p>
                       <h2>Will diabetes stop me from doing things I want to do?</h2>
                       <p>You will still be able to do all of the things you did before you had diabetes.</p>
                       <ul style="width: 60%;"><li>You will need to plan so you have time to check your blood glucose, count carbohydrates, and take insulin.</li>
                           <li>You will need to be ready for times when your blood glucose is too low or too high.</li>
                    </ul>
                    <div style="margin-top:-170px;margin-left:600px;"><img height="200px" src="img/think.jpg"></div>
        </div>
    </div>

    <!-- 2.Healthy Coping -->
    <div class="topic-box" data-popup="popup2">Healthy Coping</div>
    <!-- POPUP 2 -->
    <div class="popup" id="popup2">
        <div class="popup-content">
            <span class="close" data-close>&times;</span>

            <h2 style="text-align: center; color: darkgreen;">Healthy Coping</h2>
            <h2>Diabetes is a Family Affair</h2>
            <p>Diabetes should not define your life. It is only a part of your life. There are many changes that you and your family will need to make. We are here to 
              help you make those changes. It is our goal that you live well and continue with your regular activities.
            </p>
            <h2>The Grief Response</h2>
            <p>Any major life change can cause a grief process. Grief is the emotional response to a 
                loss or a life-changing event. The person diagnosed with diabetes and his or her family 
                members can all respond differently. </p>
                <h3 style="color:maroon"><u>Stages in the grief process may include:</u></h3>
                <ul>
                    <li>Shock -<i> “How could this be possible?”</i></li>
                    <li>Sadness -<i> “I feel different from my friends”</i></li>
                    <li>Denial -<i> “Maybe the doctors are wrong.”</i> </li>
                    <li>Helplessness - “I have no control.”</i></li>
                    <li>Anger -<i> “This isn’t fair!”</i></li>
                    <li>Anxiety -<i> “Will I be able to play basketball?”</i></li>
                    <li>Adjustment -<i> “I can still eat this snack. I just need to take insulin.”</i></li>
                    <li>Acceptance -<i> “This is my life now, so I will carry on.</i></li>
                </ul>

                <h2>Healthy Ways to Cope with Diabetes and Grief</h2>
                <ul><li>Talking to others and sharing your thoughts.</li>
                <li>Writing down your feelings.</li>
                <li>Being open to learning about diabetes.</li>
                <li>Remembering the positive things.</li>
                <li>Working together with family.</li>
               </ul>
                <h2>Unhealthy Ways to Cope with Grief Process</h2>
                <ul><li>Avoiding the fact that you have diabetes.</li>
                <li>Staying away from other people.</li>
                <li>Using drugs or drinking alcohol.</li>
                <li>Yelling at and fighting with friends, family, or other children at school.</li>
               </ul>
               <h3 style="color: maroon;"><u>Counseling Resources are Available at:</u></h3>
               <ul><li>Nationwide Children’s Hospital Psychology.</li>
                <li>Nationwide Children’s Behavioral Health Center.</li>
                <li>Other local mental health agencies.</li>
                <li>If there is an emergency needing immediate mental health attention, please 
                    call Netcare Access at 614-276-2273 or your local county mental health crisis line.</li>
               </ul>
               <h3 style="color: maroon;"><u>Family and Parenting:</u></h3>
               <ul><li> Family rules should not change because of diabetes. If a child was given 
                        certain privileges or restrictions before diagnosis, those should continue after diagnosis.</li>
                <li> Rules and privileges should be based on the child’s age, developmental stage, family needs and parenting style. </li>
                <li>Children and teens with diabetes can play sports, participate in community activities, and pursue a career of their choice (there are restrictions for 
                        military service, commercial pilots and truck drivers). </li>
                <li>IChildren and families are often better at managing diabetes when everyone works as a team. </li>
               </ul>

           
        </div>
    </div>

    <!-- 3. Type 1 Diabetes -->
    <div class="topic-box" data-popup="popup3">Monitoring Blood Glucose</div>
    <div class="popup" id="popup3">
        <div class="popup-content">
            <span class="close" data-close>&times;</span>
            <h2 style="text-align: center; color: darkgreen;">Monitoring Blood Glucose</h2>
            <h2>What is Hemoglobin A1C?</h2>
            <p>Hemoglobin A1C is a lab test. It indicates an average blood glucose reading for the 
              last 90 days. It is done when you find out you have diabetes, and every 3 months 
               after that at clinic visits. A person without diabetes has a Hemoglobin A1C of less than 5.6%</p>
               <p style="padding: 18px;border-radius: 12px;background: #fff;max-width: 700px;margin: 20px auto;
               border: 4px solid;border-image: linear-gradient(45deg, #4a90e2, #79d3ff) 1;">Target Hgb A1C is 7.5% for all children and adults with diabetes.</p>
               <p style="text-align: center;"><b>The chart below shows the Hemoglobin A1C result compared with the blood glucose number.</b></p>
            <div style="margin-left: 250px;"><img width="400px" height="320px" src="img/HBA1c.jpg" class="popup-img"></div>
            <h2>Why do I need to check my blood glucose?</h2>
            <ul><li>To keep you safe.</li>
            <li>To help you and your health care team manage your blood glucose levels.</li>
            <li>To help you change your insulin doses.</li>
             <li>To help you manage your daily activities, like school, work, exercise, and the food you eat</li>
        </ul>
        <h3 style="color:maroon"><u>Always check at these times:</u></h3>
            <ul><li>Before breakfast, lunch, and dinner.</li>
            <li> Before bed.</li>
            <li>When you have signs of low blood glucose .</li>
             <li> Each time you get in a car to drive</li>
        </ul>
        <div style="margin-top: -135px; margin-left:450px;"><h3 style="color:maroon"><u>Sometimes you will need to check at these times:</u></h3>
            <ul><li> Middle of the night</li>
            <li> Before, during, and after exercise.</li></ul></div>
        <div style="border:5px solid blue;margin-top:50px;width:700px;margin-left:100px;"><p>Check blood glucose between 2 a.m. and 4 a.m. if there is a chance your blood glucose may be low.</p>
         <ul>
            <li>Check for 2 to 3 nights after changing your Lantus® insulin dose.</li>
            <li> When you have an unusual amount of exercise late in the day.</li>
            <li>When you are sick.</li>
            <li>When your blood glucose is low before bed</li>
        </ul></div>
        <h2>What supplies do I need?</h2>
        <div class="image-grid">
    <div class="img-card">
        <img src="img/meter.jpg" >
        <p class="caption">Blood glucose meter</p>
    </div>

    <div class="img-card">
        <img src="img/strip.jpg" alt="Photo 2">
        <p class="caption">Blood glucose test strips</p>
    </div>

    <div class="img-card">
        <img src="img/lencet_device.jpg" alt="Photo 3">
        <p class="caption"> Lancet device </p>
    </div>

    <div class="img-card">
        <img src="img/lencet.jpg" alt="Photo 4">
        <p class="caption">Lancets</p>
</div></div>
           


        </div>
    </div>


    <!-- 4. Type 2 Diabetes -->
    <div class="topic-box" data-popup="popup4">
        Insulin
    </div>

    <div class="popup" id="popup4">
        <div class="popup-content">
            <span class="close" data-close>&times;</span>

            <h2>Type 2 Diabetes</h2>

            <img src="img/type2.jpg" class="popup-img">

            <ul>
                <li>More common in adults, but some children can have it.</li>
                <li>The body has insulin, but cannot use it well (insulin resistance).</li>
                <li>Can be controlled with healthy eating, exercise, and healthy weight.</li>
                <li>Sometimes pills are needed.</li>
                <li>Sometimes insulin is needed too.</li>
            </ul>
        </div>
    </div>
    <div class="topic-box" data-popup="popup4">
        Insulin
    </div>
    
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sections = document.querySelectorAll(".fade-section");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
            }
        });
    }, { threshold: 0.2 });

    sections.forEach(section => observer.observe(section));
});

</script>
<script>
document.querySelectorAll(".topic-box").forEach(box => {
    box.addEventListener("click", function() {
        const popupId = this.getAttribute("data-popup");
        document.getElementById(popupId).style.display = "flex";
    });
});

document.querySelectorAll("[data-close]").forEach(btn => {
    btn.addEventListener("click", function() {
        this.closest(".popup").style.display = "none";
    });
});

// Close if clicked outside
document.querySelectorAll(".popup").forEach(p => {
    p.addEventListener("click", function(e) {
        if (e.target === this) {
            this.style.display = "none";
        }
    });
});
</script>



</body>
</html>