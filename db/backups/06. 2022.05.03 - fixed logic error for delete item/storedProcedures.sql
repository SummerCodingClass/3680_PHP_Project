
-- for registering new users

DROP PROCEDURE IF EXISTS `RegisterUser`;

DELIMITER //

CREATE PROCEDURE `RegisterUser`(uname varchar(100), passhash text)
BEGIN
    START TRANSACTION;

    SELECT COUNT(*) INTO @usernameCount
    FROM user
    WHERE username = uname;

    IF @usernameCount > 0 THEN
        SELECT "false" as 'Result', "Username already exists" AS 'Error';
    ELSE
        INSERT INTO user (username, password) VALUES (uname, passhash);
        SELECT "true" AS 'Result', NULL as 'Error';
    END IF;
    
    COMMIT;
END;
//
DELIMITER ;



-- for registering new users

DROP PROCEDURE IF EXISTS `LogIn`;

DELIMITER //

CREATE PROCEDURE `LogIn`(uname varchar(100))
BEGIN
    START TRANSACTION;

    SELECT COUNT(*) INTO @usernameCount
    FROM user
    WHERE username = uname;

    IF @usernameCount > 0 THEN
        SELECT password INTO @matchingPassword
        FROM user
        WHERE username = uname;
        
        SELECT uid INTO @matchingUID
        FROM user 
        WHERE username = uname;
        
        SELECT @matchingUID AS 'user_id', @matchingPassword AS 'user_password', Null AS 'Error', 'true' as 'Result';
    ELSE
        SELECT NULL AS 'user_id', NULL AS 'user_password', 'user not found in our database' AS 'Error', 'false' as 'Result';
    END IF;
    
    COMMIT;
END;
//
DELIMITER ;




-- for adding items to to-do list

DROP PROCEDURE IF EXISTS `AddItem`;

DELIMITER //

CREATE PROCEDURE `AddItem`(uusername varchar(100), ulname varchar(100), 
uiname varchar(100), uicontent varchar(1000), 
uadd datetime, ugoal datetime, ucolor varchar(50))

BEGIN
    START TRANSACTION;
        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;

        SELECT lid INTO @matchingListID
        FROM list
        WHERE uid = @matchingUserID and lname = ulname;

    SELECT COUNT(*) INTO @itemnameCount
    FROM item
    WHERE lid = @matchingListID and iname = uiname;


    IF @itemnameCount > 0 THEN        
        SELECT "item name already exists in this list" AS 'Error', 'false' as 'Result';
    ELSE
        INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, color) VALUES
        (@matchingListID, uiname, uicontent, uadd, ugoal, "0", ucolor);

        SELECT LAST_INSERT_ID() INTO @newItemID;

        SELECT @newItemID as "iid", NULL AS 'Error', 'true' as 'Result';
    END IF;
    
    COMMIT;
END;
//
DELIMITER ;








-- insert statements for each table.
-- INSERT INTO user (username, password) VALUES ("test username", "test password");
-- INSERT INTO list (uid, lname, category) VALUES (1, "test lname", "test category");
-- INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, color) VALUES (1, "test item name", "test item content", "2022-12-30 00:00:00", "2022-12-30 00:00:00", "0", "test color");
-- call AddItem ("test username", "test lname", "test item from procedure", "calling procedure to insert item", "2022-02-02", "2022-02-03", "test color");






-- for deleting items from given list

DROP PROCEDURE IF EXISTS `DeleteItem`;

DELIMITER //

CREATE PROCEDURE `DeleteItem`(uusername varchar(100), ulname varchar(100), 
uiname varchar(100))

BEGIN
    START TRANSACTION;
        

    SELECT COUNT(*) INTO @matchingUserCount
    FROM user
    WHERE username =uusername;

    IF @matchingUserCount > 0 THEN
        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;


        SELECT COUNT(*) INTO @matchingListCount
        FROM list
        WHERE uid = @matchingUserID and lname = ulname;


        IF @matchingListCount > 0 THEN        

            SELECT lid INTO @matchingListID
            FROM list
            WHERE uid = @matchingUserID and lname = ulname;


            SELECT COUNT(*) INTO @itemNameCount
            FROM item
            WHERE lid = @matchingListID and iname = uiname;
        
        
            IF @itemNameCount > 0 THEN        
                SELECT iid INTO @matchingItemID
                from item
                WHERE lid = @matchingListID and iname = uiname;
        
                DELETE FROM item WHERE iid = @matchingItemID;
        
                SELECT "true" as 'Result', NULL AS 'Error';
            ELSE
                SELECT "false" as 'Result', "item does not exist" AS 'Error';
        
            END IF;


        ELSE

            SELECT "false" as 'Result', "user/list combo does not exist" AS 'Error';

        END IF;


    ELSE    

        SELECT "false" as 'Result', "user does not exist" AS 'Error';
    END IF;
    
    
    COMMIT;
END;
//
DELIMITER ;

-- call DeleteItem("test username", "test lname", "test item name4");


-- INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, color) VALUES (1, "test item name", "test item content", "2022-12-30 00:00:00", "2022-12-30 00:00:00", "0", "test color");
-- call DeleteItem("test username", "test lname", "test item name");











-- -- for marking item as done or not done

-- DROP PROCEDURE IF EXISTS `UpdateDone`;

-- DELIMITER //

-- CREATE PROCEDURE `UpdateDone`(uusername varchar(100), ulname varchar(100), 
-- uiname varchar(100), uisdone tinyint(1))

-- BEGIN
--     START TRANSACTION;
--         SELECT uid INTO @matchingUserID
--         FROM user
--         WHERE username = uusername;

--         SELECT lid INTO @matchingListID
--         FROM list
--         WHERE uid = @matchingUserID and lname = ulname;

--     SELECT COUNT(*) INTO @itemnameCount
--     FROM item
--     WHERE lid = @matchingListID and iname = uiname;


--     IF @itemnameCount > 0 THEN        
--         SELECT iid INTO @matchingItemID
--         from item
--         WHERE lid = @matchingListID and iname = uiname;

--         UPDATE item 
--         SET isdone = uisdone
--         WHERE iid = @matchingItemID;

--         SELECT "true" as 'Result', NULL AS 'Error';
--     ELSE
--         SELECT "false" as 'Result', "item does not exist" AS 'Error';

--     END IF;
    
--     COMMIT;
-- END;
-- //
-- DELIMITER ;




-- call UpdateDone("test username", "test lname", "test item from procedure", "1");







-- -- for deleting a log
-- -- ***CREATED DUPLICATE TABLE CALLED “log_backup” TO TEST THIS***

-- DROP PROCEDURE IF EXISTS `DeleteLog`;

-- DELIMITER //
-- CREATE PROCEDURE `DeleteLog` (inputLogID int)
-- BEGIN
--     START TRANSACTION;
--     SELECT COUNT(*) INTO @logCount
--     FROM log
--     WHERE logid = inputLogID;
    
--     IF @logCount > 0 THEN
--     DELETE FROM log_backup where logid = inputLogID;
--         SELECT "true" as 'Result', "log successfully deleted" AS 'Error';
    
--     ELSE
--         SELECT "false" as 'Result', "log does not exist" AS 'Error';
--     END IF;
--     COMMIT;
--  END;
-- //
-- DELIMITER ;





