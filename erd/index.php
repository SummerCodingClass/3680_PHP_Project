<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width,initial-scale=1">
    
        <title>ERD and Relational Model</title>

        <div style="margin-bottom: 70px; margin-top: 50px"> <a href="../php/home.php"><h2>View Project</h2></a> </div>
    </head>

    <body>

        <h1>ERD and Relational Model</h1>

        <h2>Entity Relationship Diagram (ERD)</h2>
        <p> tool used: https://erdplus.com/standalone </p>
        <p> click image to enlarge in a new tab </p>
        <a href="erd.png" target=_blank> <img src="erd.png" width="900px;"> <br> </a>
        <a href="erd.erdplus" target=_blank> right click + save to download the erdplus file for the diagram </a>

        <h2>Relational Model</h2>
        
        <p> good practice: </p>
        <ul>
            <li>User (<u><b>userID</b></u>, username, password, (maybe) email)</li>
            <li>List (<u><b>listID</b></u>, <i>userID</i>, listName, (maybe) category)</li>
            <li>ListItems (<i><u><b>listID</b></u></i>, <u><b>itemID</b></u>, itemName, addedDate, goalDate, completionDate, isDone, highlightedColor)</li>
            <li>UserPreference (<u><b>prefID</b></u>, <i>userID</i>, prefName, backgroundColor, fontColor, borderColor, (maybe) fontSize)</li>
        </ul>

        <p> for convenience’s sake: </p>

        <ul>
            <li> user (<b><u>uid</u></b>, username, password, email) </li>
            <li> list (<b><u>lid</u></b>, <i>uid</i>, lname, category) </li>
            <li> items ( <b><i><u>lid</u></i></b>, <b><u>iid</u></b>, iname, add, goal, completed, isdone, color) </li>
            <li> pref (<b><u>pid</u></b>, <i>uid</i>, pname, bgcolor, fontcolor,  bordercolor, fontsize) </li>
        </ul>    

        <br><br><br><br><br>

    </body>

</html>






