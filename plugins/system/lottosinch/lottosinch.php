<?php
defined('_JEXEC') or die;

class plgSystemLottosinch extends JPlugin
{
    public function onAfterInitialise()
    {
       $query = 'CREATE TABLE IF NOT EXISTS `#__lotto_bonuses` (
  `id` int(11) NOT NULL,
  `unique_number` varchar(255) NOT NULL,
  `amount` int(255) NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;




';





        $db = JFactory::getDBO();

        $db->setQuery($query);

        $result = $db->execute();

        $query = "SELECT * FROM #__users" ;
        $db->setQuery($query);

        $rows = $db->loadObjectList();
        foreach ($rows as $row) {




         //   echo $row->id.'|'.$row->username.'|'.$row->email;

            $profile = new stdClass();
            $profile->id = $row->id;
            $profile->name=$row->name;
            if ($row->block == 0){
                $profile->state=1;

            }else{

                $profile->state = 0;

            }

            $query = "SELECT * FROM #__lotto_users WHERE id='".$row->id."'";
            $db->setQuery($query);
            //echo  $query;
            $row = $db->loadResult();



            if ($row){
                // Update their details in the users table using id as the primary key.
                $result = JFactory::getDbo()->updateObject('#__lotto_users', $profile, 'id');

            }else{
                // Insert the object into the user profile table.
                 $result = JFactory::getDbo()->insertObject('#__lotto_users', $profile);

            }



        }
		$this->getAddBalance();
    }

    public function onAfterRoute()
    {
        // Do something onAfterRoute
        // eg. Test to see if this a certain component and if so run some script
    }

    function onBeforeCompileHead()
    {
        // Do something onBeforeCompileHead
        // eg. Manipulate something in the head tag
    }

    public function onAfterRender()
    {
        // Do something onAfterRender
        // eg. pull the html from the document buffer and do a string replace on something
    }
	
		
	public function getAddBalance(){
		//Load the Joomla Model framework
			jimport('joomla.application.component.model');


			//Load com_foo's foobar model. Remember the file name should be foobar.php inside the models folder
			JModelLegacy::addIncludePath(JPATH_SITE.'/administrator/components/com_lotto/models');

			//Get Instance of Model Object
			$winningIns = JModelLegacy::getInstance('winningnumbers', 'LottoModel');

			$tiraje_info = JModelLegacy::getInstance('tiraje', 'LottoModel');
			
			$users = JModelLegacy::getInstance('Users', 'LottoModel');

			$tirajes=$tiraje_info->getAlltirage();
			
				//Load com_foo's foobar model. Remember the file name should be foobar.php inside the models folder
			// JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_lotto/models');

			$usersme = JModelLegacy::getInstance('User', 'LottoModel');
			$tickets = JModelLegacy::getInstance('Tickets', 'LottoModel');		
			
			
			$db =& JFactory::getDBO();
			foreach ($users->getItems() as $user){
				
				$userId=$user->id;
				
				foreach ($tirajes as $tiraj){


					
				    $query = "SELECT * FROM #__lotto_tickets WHERE user_id=".(int) $user->id ;
                  
                    $query .= "  AND   	tickets_tiraj_numbers= '".$tiraj->id."'";
                   
					// echo $query; exit;
                    $db->setQuery($query);

                    $rows = $db->loadObjectList();
					

					
                            $tickets_tir = array();
                            foreach ($rows as $row) {

                                $tickets_tir[$row->tickets_tiraj_numbers][] = $row;

                            }
							
							foreach ( $tickets_tir as $key=>$tickets){

								$ticket_paid_total_price = 0;
                                 //Now you can call the methods inside the model
                               $winning_numbs = $winningIns->getWinningnumbers($key);


                                $tiraje_i = $tiraje_info->getTirajeInfobyID($key);


                                $winning_numbs =$winning_numbs[0];
                                $tiraje_i  =  $tiraje_i[0];

                                              //  print_r ($tiraje_i);

                                $main_winning_numbers_1 =  $winning_numbs->main_winning_numbers;
                                $main_winning_numbers =  explode(',',$main_winning_numbers_1  );
                                $additional_winning_numbers_1=  $winning_numbs->additional_winning_numbers;
                                $additional_winning_numbers =  explode(',',  $additional_winning_numbers_1  );	

								 foreach ($tickets as $tik) {
										$ticket_winning_numbers =  explode(',',$tik->numbers  );

                                                            if ($main_winning_numbers_1){
                                                               
                                                                $win_numbers_count=0;
                                                                foreach ($ticket_winning_numbers as $win_num){
                                                                    if (in_array($win_num, $main_winning_numbers)){
                                                                       
                                                                        $win_numbers_count +=1;
                                                                    }
                                                                 }
                                                            }
															$ticket_winning_numbers =  explode(',',$tik->additional_numbers  );

                                                            if ($additional_winning_numbers_1){
                                                             
                                                                $add_win_numbers_count=0;
                                                                foreach ($ticket_winning_numbers as $win_num){
                                                                    if (in_array($win_num, $additional_winning_numbers)){
                                                                        
                                                                        $add_win_numbers_count +=1;
                                                                    }
                                                                   

                                                                }
                                                            
                                                            }
															
                                                            

                                                              $winnum =  json_decode($winning_numbs->winning_prize);
                                                              $win_pr=null;

																foreach($winnum as $wnum){

																	if(($wnum[0] == $win_numbers_count) && ($wnum[1] == $add_win_numbers_count)) {

																		$win_pr =   $wnum[2];

																	}

																}
                                                              //  print_r ($winning_numbs);


																if (((empty($tik->played)) || ($tik->played == '0')) && (isset($win_pr))){



																	$usersme->update_balace($win_pr,$userId );
																//	echo "<pre>"; print_r ($tik); echo "</pre>";
																		$winningIns->getPlay($tik->tickets_id);
																	
																	
																}
																
														
																
                                                            
							
								 }									 
								
							}
					
					
								
				}
				
			}


			
		
	}


}