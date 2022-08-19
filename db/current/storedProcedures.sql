
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
uadd datetime, ugoal datetime, uishighlighted tinyint(1))

-- i changed the design so uishighlighted now more like "isHighlighted" now.
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
            INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, ishighlighted) VALUES
            (@matchingListID, uiname, uicontent, uadd, ugoal, "0", uishighlighted);

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
-- INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, ishighlighted) VALUES (1, "test item name", "test item content", "2022-12-30 00:00:00", "2022-12-30 00:00:00", "0", "test ishighlighted");
-- call AddItem ("test username", "test lname", "test item from procedure", "calling procedure to insert item", "2022-02-02", "2022-02-03", "test ishighlighted");






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


-- INSERT INTO item (lid, iname, icontent, `add`, goal, isdone, ishighlighted) VALUES (1, "test item name", "test item content", "2022-12-30 00:00:00", "2022-12-30 00:00:00", "0", "test ishighlighted");
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
-- uadd datetime, ugoal datetime, uishighlighted varchar(100))





-- for highlighting an item
-- call UpdateHighlight("1234", "default", "1234", "1");


DROP PROCEDURE IF EXISTS `UpdateHighlight`;

DELIMITER //

CREATE PROCEDURE `UpdateHighlight`(uusername varchar(100), ulname varchar(100),  
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
        
                SELECT ishighlighted INTO @currentHighlight
                from item
                WHERE iid = @matchingItemID;

                IF @currentHighlight = "1" THEN
                    -- update command
                    UPDATE item 
                    SET ishighlighted = "0"
                    WHERE iid = @matchingItemID;

                    SELECT "true" as 'Result', NULL AS 'Error';
                ELSEIF @currentHighlight = "0" THEN
                    -- update command
                    UPDATE item 
                    SET ishighlighted = "1"
                    WHERE iid = @matchingItemID;
                    
                    SELECT "true" as 'Result', NULL AS 'Error';
                ELSE
                    SELECT "false" as 'Result', "ifelse condition error" AS 'Error';
                END IF;
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

            SELECT COUNT(*) INTO @itemNewNameCount
            FROM item
            WHERE lid = @matchingListID and iname = new_uiname;

            IF @itemNewNameCount > 0 THEN        
                SELECT "false" as 'Result', "requested name already exists in this list" AS 'Error';

        
            ELSE
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
uiname varchar(100), uicontent varchar(1000), uadd datetime, ugoal datetime, uishighlighted tinyint(1))

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

                -- uicontent varchar(1000), uadd datetime, ugoal datetime, uishighlighted varchar(100))
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

                IF ISNULL(uishighlighted) THEN
                    SELECT "false" INTO @modified_ishighlighted;
                ELSE
                    UPDATE item 
                    SET ishighlighted = uishighlighted
                    WHERE iid = @matchingItemID;
                    SELECT "true" INTO @modified_ishighlighted;
                END IF;


                SELECT "true" as 'Result', NULL AS 'Error', 
                @modified_content AS "Content Modified", 
                @modified_add AS "Add Modified",
                @modified_goal AS "Goal Modified",
                @modified_ishighlighted AS "ishighlighted Modified";
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





-- for retrieving lists by category

DROP PROCEDURE IF EXISTS `FetchListsByCategory`;

DELIMITER //

CREATE PROCEDURE `FetchListsByCategory`(uusername varchar(100), ulcategory varchar(100))

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
        WHERE uid = @matchingUserID and category = ulcategory;

        IF @matchingListCount > 0 THEN        

            SELECT *, "true" as 'Result', NULL AS 'Error'
            FROM list
            WHERE uid = @matchingUserID and category = ulcategory
            order by lname;

        ELSE
            SELECT "false" as 'Result', "this category has no lists" AS 'Error';
        END IF;
    ELSE    
        SELECT "false" as 'Result', "user does not exist" AS 'Error';
    END IF;
    
    
    COMMIT;
END;
//
DELIMITER ;



-- for retrieving all categories for a user

DROP PROCEDURE IF EXISTS `FetchAllCategories`;

DELIMITER //

CREATE PROCEDURE `FetchAllCategories`(uusername varchar(100))

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
        WHERE uid = @matchingUserID;

        IF @matchingListCount > 0 THEN        

            SELECT distinct category, "true" as 'Result', NULL AS 'Error'
            FROM list
            WHERE uid = @matchingUserID
            order by category;

        ELSE
            SELECT "false" as 'Result', "this user has no lists" AS 'Error';
        END IF;
    ELSE    
        SELECT "false" as 'Result', "user does not exist" AS 'Error';
    END IF;
    
    
    COMMIT;
END;
//
DELIMITER ;




-- for retrieving all lists for a user

DROP PROCEDURE IF EXISTS `FetchAllLists`;

DELIMITER //

CREATE PROCEDURE `FetchAllLists`(uusername varchar(100))

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
        WHERE uid = @matchingUserID;

        IF @matchingListCount > 0 THEN        

            SELECT *, "true" as 'Result', NULL AS 'Error'
            FROM list
            WHERE uid = @matchingUserID
            order by lname;

        ELSE
            SELECT "false" as 'Result', "this user has no lists" AS 'Error';
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












-- displaying items by add time 

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






-- displaying items by goal time 

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






-- displaying items by completed time 

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






-- delete all items in either todo list or done list

DROP PROCEDURE IF EXISTS `DeleteAllListItems`;
DELIMITER //
CREATE PROCEDURE `DeleteAllListItems`(uusername varchar(100), ulname varchar(100), 
uisdone tinyint(1))
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
                IF uisdone = "0" THEN
                    -- SELECT "true" AS 'Result', *
                    -- FROM item
                    -- WHERE lid = @matchingListID and isdone = "0"
                    -- ORDER BY `add` asc;

                    DELETE FROM item
                    WHERE lid = @matchingListID and isdone = "0";
                    
                    SELECT "true" as 'Result', NULL as 'Error';

                ELSEIF uisdone = "1" THEN
                    DELETE FROM item
                    WHERE lid = @matchingListID and isdone = "1";

                    SELECT "true" as 'Result', NULL as 'Error';

                ELSE
                    SELECT "false" as 'Result', "invalid isdone requested" AS 'Error';
                END IF;
            ELSE
                SELECT "false" as 'Result', "this list has no items" AS 'Error';
    
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





-- preferences



-- for adding a preference set

DROP PROCEDURE IF EXISTS `AddPreference`;

DELIMITER //

CREATE PROCEDURE `AddPreference`(uusername varchar(100), upname varchar(100), 
ubgcolor varchar(100), ufontcolor varchar(100), ubordercolor varchar(100), 
ufontsize varchar(100), uhighlightcolor varchar(100))

BEGIN
    START TRANSACTION;
        SELECT COUNT(*) INTO @matchingUserCount
        FROM user
        WHERE username = uusername;

        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;

        SELECT COUNT(pid) INTO @matchingPrefCount
        FROM pref
        WHERE uid = @matchingUserID and pname = upname;

        IF @matchingUserCount > 0 THEN        

            IF @matchingPrefCount > 0 THEN        
                SELECT "pref name already exists for this user" AS 'Error', 'false' as 'Result';
            ELSE
                INSERT INTO pref (uid, pname, bgcolor, fontcolor, bordercolor, fontsize, highlightcolor) VALUES
                (@matchingUserID, upname, ubgcolor, ufontcolor, ubordercolor, ufontsize, uhighlightcolor);

                SELECT LAST_INSERT_ID() INTO @newPrefID;

                -- again pid isn't really needed/used in my project, but doesn't hurt to keep this structure
                -- in case of future redesigning.
                SELECT @newPrefID as "pid", NULL AS 'Error', 'true' as 'Result';
            END IF;
        ELSE
            SELECT "user does not exist" AS 'Error', 'false' as 'Result';
        END IF;
    
    COMMIT;
END;
//
DELIMITER ;



--  call AddPreference ("1234", "default", "white", "black", "black", "20", "yellow");
--  call UpdatePreference ("1234", "default", "#FFFFFF", "#000000", "#000000", "16", "#FFFF00");


-- for updating a preference set

DROP PROCEDURE IF EXISTS `UpdatePreference`;

DELIMITER //

CREATE PROCEDURE `UpdatePreference`(uusername varchar(100), upname varchar(100), 
ubgcolor varchar(100), ufontcolor varchar(100), ubordercolor varchar(100), 
ufontsize varchar(100), uhighlightcolor varchar(100))

BEGIN
    START TRANSACTION;
        SELECT COUNT(*) INTO @matchingUserCount
        FROM user
        WHERE username = uusername;

        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;

        SELECT COUNT(pid) INTO @matchingPrefCount
        FROM pref
        WHERE uid = @matchingUserID and pname = upname;

        IF @matchingUserCount > 0 THEN        

            IF @matchingPrefCount > 0 THEN        
                -- UPDATE pref SET (bgcolor, fontcolor, bordercolor, fontsize, highlightcolor) VALUES
                -- (ubgcolor, ufontcolor, ubordercolor, ufontsize, uhighlightcolor) 
                -- WHERE uid = @matchingUserID and pname = upname;

                UPDATE pref SET 
                bgcolor = ubgcolor, 
                fontcolor = ufontcolor, 
                bordercolor = ubordercolor, 
                fontsize = ufontsize, 
                highlightcolor = uhighlightcolor 
                WHERE uid = @matchingUserID and pname = upname;

                SELECT pid INTO @newPrefID
                from pref
                WHERE uid = @matchingUserID and pname = upname;

                -- again pid isn't really needed/used in my project, but doesn't hurt to keep this structure
                -- in case of future redesigning.
                SELECT @newPrefID as "pid", NULL AS 'Error', 'true' as 'Result';
            ELSE
                SELECT "pref name does not exist for this user" AS 'Error', 'false' as 'Result';
            END IF;
        ELSE
            SELECT "user does not exist" AS 'Error', 'false' as 'Result';
        END IF;
    
    COMMIT;
END;
//
DELIMITER ;







-- for retrieving a set of preferences for a user

DROP PROCEDURE IF EXISTS `FetchAPreference`;

DELIMITER //

CREATE PROCEDURE `FetchAPreference`(uusername varchar(100), upname varchar(100))

BEGIN
    START TRANSACTION;
        

    SELECT COUNT(*) INTO @matchingUserCount
    FROM user
    WHERE username =uusername;

    IF @matchingUserCount > 0 THEN
        SELECT uid INTO @matchingUserID
        FROM user
        WHERE username = uusername;

        SELECT COUNT(*) INTO @matchingPrefCount
        FROM pref
        WHERE uid = @matchingUserID and pname = upname;

        IF @matchingPrefCount > 0 THEN        

            SELECT *, "true" as 'Result', NULL AS 'Error'
            FROM pref
            WHERE uid = @matchingUserID and pname = upname
            order by pname;

        ELSE
            SELECT "false" as 'Result', "this user has no preference of this name" AS 'Error';
        END IF;
    ELSE    
        SELECT "false" as 'Result', "user does not exist" AS 'Error';
    END IF;
    
    
    COMMIT;
END;
//
DELIMITER ;

