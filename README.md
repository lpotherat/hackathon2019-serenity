# hackathon2019-serenity
Tag link example : sty://tag/84588701-029e-44f7-8784-d6f44d7464ca

# API Documentation

### Start
Start a session with /api/session/start . To start, you must send latitude and longitude of the user.

    Method : POST
    Content-type : JSON
    {
    	"lat":4.0,
    	"lgt":46.0
    }

Return : 

    Content-type : JSON
    {
    	"result":true, //or false 
    	"message":"A fancy message"
    }

### Follow  
While the user is driving, make a periodic call at /api/session/follow . Latitude and longitude are mandatory, start should have been called before.

    Method : POST
    Content-type : JSON
    {
    	"lat":4.0,
    	"lgt":46.0
    }

Return : 

    Content-type : JSON
    {
    	"result":true, //or false 
    	"message":"A fancy message"
    }
    
### Check   
When the user checks in at a Serenity Checkpoint, the app should transmit the NFC tag UUID to /api/check .
The NFC Tag will launch your app with the URI sty://tag/ce29c0ec-e9cb-4b2f-b97b-106a9723a058 .

    Method : POST
    Content-type : JSON
    {
    	"uuid":"ce29c0ec-e9cb-4b2f-b97b-106a9723a058"
    }
    
    
Returns the offers list
    
