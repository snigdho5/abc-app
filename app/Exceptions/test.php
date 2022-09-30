<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>Welcome to ::::* The Eye Clinic *::::</title>

        <link href="stylesheet.css" rel="stylesheet" type="text/css" />



        <link rel="stylesheet" type="text/css" href="jqueryslidemenu.css" />







        <link href="utils/slimbox.css" rel="stylesheet" type="text/css" media="screen" />

        <script type="text/javascript" src="jquery.min.js"></script>

        <script type="text/javascript" src="jqueryslidemenu.js"></script>

        <script type="text/javascript" src="utils/mootools.js"></script>

        <script type="text/javascript" src="utils/slimbox.js"></script>





        <!--[if lte IE 7]>
        
        <style type="text/css">
        
        html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
        
        </style>
        
        <![endif]-->

        <script language="JavaScript1.2">mmLoadMenus();</script>

        <SCRIPT language=JavaScript>

            function validateEmailv2(email)

            {

                if (email.length <= 0)

                {

                    return true;

                }

                var splitted = email.match("^(.+)@(.+)$");

                if (splitted == null)
                    return false;

                if (splitted[1] != null)

                {

                    var regexp_user = /^\"?[\w-_\.]*\"?$/;

                    if (splitted[1].match(regexp_user) == null)
                        return false;

                }

                if (splitted[2] != null)

                {

                    var regexp_domain = /^[\w-\.]*\.[A-Za-z]{2,4}$/;

                    if (splitted[2].match(regexp_domain) == null)

                    {

                        var regexp_ip = /^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;

                        if (splitted[2].match(regexp_ip) == null)
                            return false;

                    }// if

                    return true;

                }

                return false;

            }



            function IsNumeric(strString)

        // check for valid numeric strings

            {

                var strValidChars = "0123456789,()-";

                var strChar;

                var blnResult = true;



                if (strString.length == 0)
                    return false;



        // test strString consists of valid characters listed above

                for (i = 0; i < strString.length && blnResult == true; i++)

                {

                    strChar = strString.charAt(i);

                    if (strValidChars.indexOf(strChar) == -1)

                    {

                        blnResult = false;

                    }

                }

                return blnResult;

            }







            function validate_required(field, alerttxt)

            {

                with (field)

                {

                    if (value == null || value == "")

                    {
                        alert(alerttxt);
                        return false
                    } else {
                        return true
                    }

                }

            }





            function validate_form(thisform)

            {

                with (thisform)

                {

                    if (validate_required(name, "Enter your name!") == false)

                    {

                        name.focus();

                        return false

                    }





                    if (validate_required(email, "Enter your Email address!") == false)

                    {

                        email.focus();

                        return false

                    }

                    if (validateEmailv2(document.myqueryform.email.value) == false)

                    {

                        alert('Email address is not valid');

                        document.myqueryform.email.focus();

                        return false;

                    }

                    if (validate_required(phone, "Enter your phone number!") == false)

                    {

                        phone.focus();

                        return false

                    }

                    if (IsNumeric(document.myqueryform.phone.value) == false)

                    {

                        alert('Enter phone is not valid');

                        document.myqueryform.phone.focus();

                        return false;

                    }



                    if (validate_required(messagetxt, "Enter Message!") == false)

                    {

                        messagetxt.focus();

                        return false

                    }



                    if (document.myqueryform.security_code.value == '')

                    {

                        alert('Enter Security Code');

                        document.myqueryform.security_code.focus();

                        return false;

                    }





                }

            }

        </script>







    </head>



    <body>



        <div id="bodybg">  <div id="topbox"> <div id="logoox"> <a href="index.html" id="logo3"></a> </div>





                <div id="myslidemenu" class="jqueryslidemenu">

                    <ul>

                        <li><a href="index.html"><img src="images/home.png" alt="Home" /> </a></li>



                        <li><a href="aboutus.html">About Us</a>

                            <ul>

                                <li><a href="clinic_profile.html">TEC Profile </a></li>

                                <li><a href="doctor_Profile.html">Doctor’s profile </a></li>

                                <li><a href="tec_team.html">TEC Team </a></li>

                                <li><a href="infrastructure.html">Infrastructure</a></li>

                                <li><a href="facilities.html">Facilities</a></li>

                                <li><a href="empanelments.html">Empanelments</a></li>

                            </ul>

                        </li>

                        <li><a href="#">Services</a>

                            <ul>

                                <li><a href="diagnostics.html">Diagnostics  </a></li>

                                <li><a href="surgeries.html">Surgery </a></li>



                            </ul>

                        </li>

                        <li><a href="community.html">Community</a></li>

                        <li><a href="contactus.php">Contact us</a></li>

                    </ul>

                    <br style="clear: left" />

                </div>



                <div style="clear:both;"></div>

            </div>

        </div>



        <div class="fxd_pnl">

            <a href="https://twitter.com/retinatec" target="_blank" class="ld_stky_btn"><b></b><span>Follow Us</span></a>

            <a href="https://www.facebook.com/The-Eye-Clinic-218389461519445/?ref=settings" target="_blank" class="ofr_btn"><b></b><span>Facebook</span></a>

            <a href="contactus.php" id="gst-btn" class="gst_btn" style="cursor: pointer;"><b></b><span>Make an Appointment</span></a>



        </div>

        <div id="contentbox1">

            <div id="contentbox">

                <div id="topheader2">

                    <div id="subheader">

                        <img src="images/banner7.jpg" />

                    </div>



                </div>

                <div id="contentarea">

                    <div id="box1b">

                        <div align="justify" class="txt2" id="txtbox3b">

                            <h2>Contact Us</h2>

                            <br />

                            <br />

                            <strong>THE EYE CLINIC, </strong><br />

                            Centre for Retina &amp; Lasers,<br />

                            3 A, Chakrata Road, Dehradun 248 001 <br />

                            Email Id: <a href="mailto:info@the-eye-clinic.in" class="txt14">info@the-eye-clinic.in</a><br />

                            Mobile no. +91 94120 53069, +91 94111 63069<br />

                            Phone no. 0135- 272 3069<br /><br />



                            <strong>MONTHLY CONSULTATIONS : </strong><br />

                            <strong>Prakash Eye Hospital & Laser Centre</strong>,<br />

                            D1-d2, Lane 2, Doctors Colony, Civil Lines,<br />

                            Rudrapur, Uttarakhand<br />

                            Telephone: (91)-(5944)-246946<br /><br />



   <!--<strong>Drishti, Centre for Advanced Eye Care</strong>,<br />

   Durga City Center, Naintal Road,<br />

   Haldwani (Nainital), Uttarakhand<br />

   Telephone: 91-(5946)-280777

  <br /><br />

  <p>Retina  Specialist from TEC Dehradun, provides specialized retina services to various  peripheral eye clinics and centres in Uttarakhand and adjoining regions<br />-->

                            <br />

                            <!--Monthly  Consultation at New Delhi (Contact: 09759170604)&nbsp;--></p>

                            <br />

                            <br />



                            <div id="formsbox">





                                <form action="submit.php" method="post" class="cssform" name="myqueryform" id="myqueryform" onsubmit="return validate_form(this)">

                                    <span class="txt9">Book an appointment</span><br />



                                    <span style="font-size:11px; color:#666">Please fill out this form. We will get in touch with you shortly</span>

                                    <br />

                                    <br />



                                    <p>

                                        <label for="user">Your Name*</label>

                                        <input name="name" type="text" id="name" style="width:250px;" value="" />

                                    </p>



                                    <p>

                                        <label for="emailaddress">Your E-mail*</label>

                                        <input name="email" type="text" id="email" style="width:250px;" value="" />

                                    </p>



                                    <p>

                                        <label for="emailaddress">Your Phone</label>

                                        <input name="phone" type="text" id="phone" style="width:250px;" value="" />

                                    </p>



                                    <p>

                                        <label for="comments">Message*</label>

                                        <textarea name="messagetxt" cols="25" rows="5" id="messagetxt"></textarea>

                                        <br />

                                        <img src="CaptchaSecurityImages.php?width=100&height=40&characters=5" width="70" height="24" style="border:1px solid #269E9A; margin-top:5px; margin-bottom:5px;" /><br />

                                        <label for="security_code">Security Code: </label><input id="security_code" name="security_code" type="text" /><br />

                                        <div style="margin-left: 155px;">





                                            <input type="submit" class="sendbutton" name="submit1"  value="submit" />

                                        </div>

                                </form>



                            </div>

                            <br />





                            </a></p>

                        </div>

                    </div>

                    <div id="box2b"><br />

                        <div class="txt13" id="doctorschedulebox">TEC schedule:</div>

                        <div id="doctorschedule">

                            <div class="txt12" id="txtbox1">Days</div>

                            <div class="txt12" id="txtbox1a">Morning session </div>

                            <div class="txt12" id="txtbox1b">Evening session </div>



                        </div>

                        <div id="doctorschedule2">

                            <div id="txtbox2a">

                                <div id="schedule">

                                    <ul>

                                        <li class="txt15">Monday</li>

                                        <li class="txt15">Tuesday</li>

                                        <li class="txt15">Wednesday</li>

                                        <li class="txt15">Thursday</li>

                                        <li class="txt15">Friday</li>

                                        <li class="txt15">Saturday</li>



                                    </ul>

                                </div>

                            </div>

                            <div id="txtbox2b">

                                <div id="schedule">

                                    <ul>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                        <li class="txt15">9.30 am-2.00 pm</li>

                                    </ul>

                                </div>

                            </div>

                            <div id="txtbox2c">

                                <div id="schedule">

                                    <ul>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                        <li class="txt15">5.00 pm-7.00 pm</li>

                                    </ul>

                                </div>

                            </div>

                            <div class="txt3" id="schedulebox2">Consultation By Appointment Only</div>

                            <div style="clear:both;"></div>

                        </div>

                    </div>



                    <div id="map"><span class="txt13">Route map</span><img src="images/Map.jpg" width="343" height="200" style="float:left;" /></div>

                    <div id="enlargeimage"><a rel="lightbox[gallery]" rev="Studio Brahma 1 " href="images/Map_big.jpg" title="Route map" class="link11"><img src="images/zoom_but.jpg" width="129" height="25" border="0" /></a></div>





                    <div style="clear:both;"></div>



                </div>

                <div class="lpd_logo"><img src="images/enabh_logo.jpg" alt="enabh Logo"  align="right"/></div>

                <div style="clear:both;"></div>

            </div>

        </div>

        <div id="footerbg">

            <div id="footerbox">

                <div class="txt8" id="copyright">© Copyright 2019 The Eye Clinic. All Rights Reserved</div>

                <div id="foterlink"><a href="sitemap.html" class="txt8">Site map</a> <span class="txt8">|</span> <a href="disclaimer.html" class="txt8">Disclaimer</a></div>

            </div>

        </div>

        <script type="text/javascript">

            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");

            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));

        </script>

        <script type="text/javascript">

            try {

                var pageTracker = _gat._getTracker("UA-16275731-1");

                pageTracker._trackPageview();

            } catch (err) {
    }</script>

    </body>

</html>

