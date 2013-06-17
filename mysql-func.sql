DELIMITER $$  
	  /*----------------insert one row into story_types table----------*/
	DROP FUNCTION IF EXISTS `calc_distance`$$
	
	CREATE FUNCTION calc_distance (grid_n1 INT(11),grid_e1 INT(11), pcode2 VARCHAR(255))
	RETURNS INT(10) 
	 BEGIN
		DECLARE grid_n2 INT;
		DECLARE grid_e2 INT;
		DECLARE distance_n DECIMAL(10,2);
		DECLARE kms DECIMAL(10,2);
		DECLARE distance_e DECIMAL(10,2);
		DECLARE hypot DECIMAL(10,2);
		  
		DECLARE EXIT HANDLER FOR SQLEXCEPTION
		  BEGIN		      
		      RETURN -1;
		  END; 
		  		
		IF (LENGTH(pcode2)<2 || LENGTH(pcode2)>4) THEN 
			RETURN -1;
		END IF;
		
		SELECT `postcodes`.`Grid_E` ,`postcodes`.`Grid_N` INTO grid_e2,grid_n2
			FROM `postcodes`  WHERE `postcodes`.`Pcode`=pcode2;
			
                IF (ISNULL(grid_e2) || ISNULL(grid_n2)) THEN 
			RETURN -1; 
		END IF;
		
		SET distance_n=grid_n1 - grid_n2;		
                SET distance_e=grid_e1 - grid_e2;
		
		SET hypot=SQRT((distance_n*distance_n)+(distance_e*distance_e));
		
                SET kms=ROUND(hypot/1000,2);
                  
                RETURN kms;
              
	  END$$
DELIMITER ;

SELECT  calc_distance(392900,804900,'AB11')
