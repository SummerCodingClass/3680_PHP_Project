
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



-- for returning users

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
uadd datetime, ugoal datetime, ucolor tinyint(1))

-- i changed the design so ucolor now more like "isHighlighted" now.
-- all items are not highlighted when they first got added....
-- but i kept the structure to avoid mass changing rn.
-- so this logic is being put in where I call the stored procedure in php.

BEGIN
    START TRANSACTION;

    SELECT COUNT(*) INTO @matchingUserCount
    FROM user
    WHERE username =uusername;

    IF @matchingUserCount > 0 THEN    

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

    ELSE
        SELECT "user does not exist" AS 'Error', 'false' as 'Result';
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
        

                -- delete command
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











-- for marking item as done or not done

DROP PROCEDURE IF EXISTS `UpdateDone`;

DELIMITER //

CREATE PROCEDURE `UpdateDone`(uusername varchar(100), ulname varchar(100),  
uiname varchar(100), uisdone tinyint(1), ucompleted datetime)

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
        
                -- update command
                UPDATE item 
                SET isdone = uisdone
                WHERE iid = @matchingItemID;
                
                IF uisdone = "1" THEN
                    update item 
                    SET completed = ucompleted
                    WHERE iid = @matchingItemID;
                END IF;    
        
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

-- call UpdateDone ("12345678", "default", "abcd", "1", "2022-05-15 20:20:20");
-- call UpdateDone ("12345678", "default", "abcd", "0", "2022-05-15 01:01:01");



-- call UpdateDone("test username", "test lname", "test item from procedure", "1");
-- uiname varchar(100), uicontent varchar(1000), 
-- uadd datetime, ugoal datetime, ucolor varchar(100))







-- for updating item name

DROP PROCEDURE IF EXISTS `UpdateItemName`;

DELIMITER //

CREATE PROCEDURE `UpdateItemName`(uusername varchar(100), ulname varchar(100), 
uiname varchar(100), new_uiname varchar(100))

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
        
                -- update command
                UPDATE item 
                SET iname = new_uiname
                WHERE iid = @matchingItemID;
        
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











-- updating item attributes

DROP PROCEDURE IF EXISTS `UpdateItem`;

DELIMITER //

CREATE PROCEDURE `UpdateItem`(uusername varchar(100), ulname varchar(100), 
uiname varchar(100), uicontent varchar(1000), uadd datetime, ugoal datetime, ucolor tinyint(1))

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
        
                -- action items

                -- uicontent varchar(1000), uadd datetime, ugoal datetime, ucolor varchar(100))
                -- https://mysqlcode.com/mysql-isnull-ifnull/
                IF ISNULL(uicontent) THEN
                -- IF (uicontent = "NULL")
                    SELECT "false" INTO @modified_content;
                ELSE
                    UPDATE item 
                    SET icontent = uicontent
                    WHERE iid = @matchingItemID;

                    SELECT "true" INTO @modified_content;
                END IF;


                IF ISNULL(uadd) THEN
                    SELECT "false" INTO @modified_add;
                ELSE
                    UPDATE item 
                    SET `add` = uadd
                    WHERE iid = @matchingItemID;

                    SELECT "true" INTO @modified_add;
                END IF;
                
        
                IF ISNULL(ugoal) THEN
                    SELECT "false" INTO @modified_goal;
                ELSE
                    UPDATE item 
                    SET goal = ugoal
                    WHERE iid = @matchingItemID;
                    SELECT "true" INTO @modified_goal;
                END IF;

                IF ISNULL(ucolor) THEN
                    SELECT "false" INTO @modified_color;
                ELSE
                    UPDATE item 
                    SET color = ucolor
                    WHERE iid = @matchingItemID;
                    SELECT "true" INTO @modified_color;
                END IF;


                SELECT "true" as 'Result', NULL AS 'Error', 
                @modified_content AS "Content Modified", 
                @modified_add AS "Add Modified",
                @modified_goal AS "Goal Modified",
                @modified_color AS "Color Modified";
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






-- for adding a list

DROP PROCEDURE IF EXISTS `AddList`;

DELIMITER //

CREATE PROCEDURE `AddList`(uusername varchar(100), ulname varchar(100), 
ulcategory varchar(100))

BEGIN
    START TRANSACTION;
        SELECT COUNT(*) INTO @matchingUserCount
        FROM user
        WHERE username = uusername;

        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;

        SELECT COUNT(lid) INTO @matchingListCount
        FROM list
        WHERE uid = @matchingUserID and lname = ulname;

        IF @matchingUserCount > 0 THEN        

            IF @matchingListCount > 0 THEN        
                SELECT "list name already exists for this user" AS 'Error', 'false' as 'Result';
            ELSE
                INSERT INTO list (uid, lname, category) VALUES
                (@matchingUserID, ulname, ulcategory);

                SELECT LAST_INSERT_ID() INTO @newListID;

                SELECT @newListID as "lid", NULL AS 'Error', 'true' as 'Result';
            END IF;
        ELSE
            SELECT "user does not exist" AS 'Error', 'false' as 'Result';
        END IF;
    
    COMMIT;
END;
//
DELIMITER ;






-- for deleting a list

DROP PROCEDURE IF EXISTS `DeleteList`;

DELIMITER //

CREATE PROCEDURE `DeleteList`(uusername varchar(100), ulname varchar(100))

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

            -- delete command
            DELETE FROM list WHERE lid = @matchingListID;
            SELECT "true" as 'Result', NULL AS 'Error';

        ELSE
            SELECT "false" as 'Result', "list name does not exist for this user" AS 'Error';
        END IF;
    ELSE    
        SELECT "false" as 'Result', "user does not exist" AS 'Error';
    END IF;
    
    
    COMMIT;
END;
//
DELIMITER ;













-------------------------------------------------------------------
-- found. problem was i forgot the "then" in "if.. then" x)
-------------------------------------------------------------------
-- debugging: 


-- DROP PROCEDURE IF EXISTS `test`;

-- DELIMITER //

-- CREATE PROCEDURE `test`(testVar varchar(100))

-- BEGIN
--     START TRANSACTION;

--         IF ISNULL(testVar) THEN
--         -- IF (uicontent = "NULL")
--             -- SELECT "false" INTO @modified_content;
--             -- SELECT "false" AS '@modified_content';
--             SELECT "false" AS 'Result';
--         ELSE
--             -- SELECT "true" INTO @modified_content;
--             -- SELECT "true" AS '@modified_content';
--             SELECT "true" AS 'Result';
--         END IF;

--     COMMIT;
-- END;
-- //
-- DELIMITER ;



-- DROP PROCEDURE IF EXISTS `test`;

-- DELIMITER //

-- CREATE PROCEDURE `test`(testVar varchar(100))

-- BEGIN
--     START TRANSACTION;

--         -- SELECT ISNULL(testVar) into @testVarIsNull;
--         SELECT ISNULL(testVar) into @testVarIsNull;

--         IF @testVarIsNull > 0 THEN
--         -- IF (uicontent = "NULL")
--             -- SELECT "false" INTO @modified_content;
--             -- SELECT "false" AS '@modified_content';
--             SELECT "false" as 'Result';
--         ELSE
--             -- SELECT "true" INTO @modified_content;
--             -- SELECT "true" AS '@modified_content';
--             SELECT "true" AS 'Result';
--         END IF;

--     COMMIT;
-- END;
-- //
-- DELIMITER ;












-- displaying logs by add time 

DROP PROCEDURE IF EXISTS `DisplayItemsByAddTime`;
DELIMITER //
CREATE PROCEDURE `DisplayItemsByAddTime`(uusername varchar(100), ulname varchar(100), 
uisdone tinyint(1), uorder varchar(100))
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
            WHERE lid = @matchingListID;

            IF @itemNameCount > 0 THEN        
                -- action items
                -- to do list
                IF (uorder = "ascend" AND uisdone = "0") THEN
                    -- SELECT "true" AS 'Result', *
                    -- FROM item
                    -- WHERE lid = @matchingListID and isdone = "0"
                    -- ORDER BY `add` asc;

                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `add` asc;


                ELSEIF (uorder = "descend" AND uisdone = "0") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `add` desc;           

                -- done list
                 -- action items
                ELSEIF (uorder = "ascend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `add` asc;

                ELSEIF (uorder = "descend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `add` desc;       
                ELSE
                    SELECT "false" as 'Result', "invalid order" AS 'Error';
                END IF;
            ELSE
                SELECT "false" as 'Result', "no items in the list" AS 'Error';
    
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


-- the invalid order error msg helped... just saying.
-- call DisplayItemsByAddTime("12345678", "default", "0", "ascend");
-- call DisplayItemsByAddTime("12345678", "default", "0", "descend");



-- -- original structure. outdated.

-- DROP PROCEDURE IF EXISTS `DisplayItemsByGoalTime`;
-- DELIMITER //
-- CREATE PROCEDURE `DisplayItemsByGoalTime`(uusername varchar(100), ulname varchar(100), 
-- uascen varchar(100))
-- BEGIN
--     START TRANSACTION;
--     SELECT COUNT(*) INTO @matchingUserCount
--     FROM user
--     WHERE username =uusername;
--     IF @matchingUserCount > 0 THEN
--         SELECT uid INTO @matchingUserID
--         FROM user
--         WHERE username = uusername;
--         SELECT COUNT(*) INTO @matchingListCount
--         FROM list
--         WHERE uid = @matchingUserID and lname = ulname;
--         IF @matchingListCount > 0 THEN        
--             SELECT lid INTO @matchingListID
--             FROM list
--             WHERE uid = @matchingUserID and lname = ulname;
--             SELECT COUNT(*) INTO @itemNameCount
--             FROM item
--             WHERE lid = @matchingListID;
--             IF @itemNameCount > 0 THEN        
--                 -- action items
--                 IF uascen = "true" THEN
--                     SELECT "true" AS 'Result', *
--                     FROM item
--                     WHERE lid = @matchingListID
--                     ORDER BY `goal` asc;
--                 ELSE IF uascen = "false" THEN
--                     SELECT "true" AS 'Result', *
--                     FROM item
--                     WHERE lid = @matchingListID
--                     ORDER BY `goal` desc;                
--                 ELSE
--                     SELECT "false" as 'Result', "asc problem" AS 'Error';
--                 END IF;
--             ELSE
--                 SELECT "false" as 'Result', "no items in the list" AS 'Error';
    
--             END IF;
--         ELSE
--             SELECT "false" as 'Result', "user/list combo does not exist" AS 'Error';
--         END IF;
--     ELSE    
--         SELECT "false" as 'Result', "user does not exist" AS 'Error';
--     END IF;    
--     COMMIT;
-- END;
-- //
-- DELIMITER ;






-- displaying logs by goal time 

DROP PROCEDURE IF EXISTS `DisplayItemsByGoalTime`;
DELIMITER //
CREATE PROCEDURE `DisplayItemsByGoalTime`(uusername varchar(100), ulname varchar(100), 
uisdone tinyint(1), uorder varchar(100))
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
            WHERE lid = @matchingListID;

            IF @itemNameCount > 0 THEN        
                -- action items
                -- to do list
                IF (uorder = "ascend" AND uisdone = "0") THEN
                    -- SELECT "true" AS 'Result', *
                    -- FROM item
                    -- WHERE lid = @matchingListID and isdone = "0"
                    -- ORDER BY `add` asc;

                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `goal` asc;


                ELSEIF (uorder = "descend" AND uisdone = "0") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `goal` desc;           

                -- done list
                 -- action items
                ELSEIF (uorder = "ascend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `goal` asc;

                ELSEIF (uorder = "descend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `goal` desc;       
                ELSE
                    SELECT "false" as 'Result', "invalid order" AS 'Error';
                END IF;
            ELSE
                SELECT "false" as 'Result', "no items in the list" AS 'Error';
    
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






-- displaying logs by completed time 

DROP PROCEDURE IF EXISTS `DisplayItemsByCompletedTime`;
DELIMITER //
CREATE PROCEDURE `DisplayItemsByCompletedTime`(uusername varchar(100), ulname varchar(100), 
uisdone tinyint(1), uorder varchar(100))
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
            WHERE lid = @matchingListID;

            IF @itemNameCount > 0 THEN        
                -- action items
                -- to do list
                IF (uorder = "ascend" AND uisdone = "0") THEN
                    -- SELECT "true" AS 'Result', *
                    -- FROM item
                    -- WHERE lid = @matchingListID and isdone = "0"
                    -- ORDER BY `add` asc;

                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `completed` asc;


                ELSEIF (uorder = "descend" AND uisdone = "0") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "0"
                    ORDER BY `completed` desc;           

                -- done list
                 -- action items
                ELSEIF (uorder = "ascend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `completed` asc;

                ELSEIF (uorder = "descend" AND uisdone = "1") THEN
                    SELECT *, "true" AS 'Result', NULL AS 'Error'
                    FROM item
                    WHERE lid = @matchingListID and isdone = "1"
                    ORDER BY `completed` desc;       
                ELSE
                    SELECT "false" as 'Result', "invalid order" AS 'Error';
                END IF;
            ELSE
                SELECT "false" as 'Result', "no items in the list" AS 'Error';
    
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




