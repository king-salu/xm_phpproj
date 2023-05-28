# XM Test Project PHP Exercise - v21.0.5

---

:scroll: **START**


Using the Symfony PHP Framework, the application is designed as a single page with a **Company** form with the following fields
* Company Symbol _(Which is a dropdown of fetched company symbols from predefined data)_
* Start Date _(Text field with a datepicker ui using the date format YYYY-mm-dd)_
* End Date _(Text field with a datepicker ui using the date format YYYY-mm-dd)_
* Email _(Text field for email input)_  
> ℹ️ The predefined data for the company info were provided via json from this link [Here](https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json)

## Requirements
After submission, the following are required of the application:
* Validate the form both on client and server side and display the appropriate messages on both cases. with Validation rules:
  - _**Company Symbol**: required, valid symbol_
  - _**Start Date**: required, valid date, less or equal than End Date, less or equal than current date_
  - _**End Date**: required, valid date, greater or equal than Start Date, less or equal than current date_
  - _**Email**: required, valid email_  
 
* Display on screen the historical quotes for the submitted Company Symbol in the given date range in table format. Table columns\
**Date | Open | High | Low | Close | Volume**

* Based on the Historical data retrieved, display on screen a chart of the Open and Close prices.

* Send to the submitted Email an email message.

## Implementation
This project is implemented and deployed on **localhost**, based on the requirements some of the following libaries, plugin/Component or tools were used to execute task
* Datepicker Ui: Used jQuery datepicker Ui from [Here](http://jqueryui.com/datepicker/).
* Chart Ui: Used Chart.js from [Here](https://www.chartjs.org/).
* Historical Quotes: Historical data are fetched from the secured link [Here](https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol=AMRN&region=US)\
> ℹ️ Using the given header parameters  
>> X-RapidAPI-Key: xxxxxxxxxxxxxxxxxx  
>> X-RapidAPI-Host: yh-finance.p.rapidapi.com  
>
> ⚠️ _Please note: these headers are defined at the environment **.env** file_
* Mail Service: The mail service used was [Mailtrap](https://mailtrap.io/home) which was run on test mode
> ℹ️ Using Company name as the **Subject**  
> and the Start date and end date as the **Body**  
> ⚠️ _Please note: All mails sent or tried will not deliver to recipient, rather will be delivered to the inbox of mailtrap owner (me)_  
> _To test this with an active mail service, please change the **MAILER_DSN** variable in the environment **.env** file to your preferred DSN configuration._

The routes for this application are as following
* Company Form Page\
**[ GET | POST ] _{url}_**_/_

---

:scroll: **END**
