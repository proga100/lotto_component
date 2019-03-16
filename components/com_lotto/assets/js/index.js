
jQuery( document ).ready(function() {

    var tiraje_amount = 1;
    var tiraje_tickets = 3;
    var tickets = {};
   var additional_fields_tickets={};

    if (localStorage.getItem('tirajTotal')){
        var tiraje_tickets = localStorage.getItem('tirajTotal');

    }else{
        var tiraje_tickets = 3;
    }
    var tiraje_tickets = 3;
    if (localStorage.getItem('tirajTotalTickets')){

       var tirajTotalTickets =  JSON.parse(localStorage.getItem('tirajTotalTickets'));

        console.log(tirajTotalTickets);
    }

   // set_tiraje_total(tiraje_tickets);

    set_colors_clicked("#col_tiraj_"+tiraje_amount);
    set_colors_clicked("#col_ticket_"+tiraje_tickets);



    jQuery("#col_ticket_total").text(tiraje_amount);
    jQuery("#col_ticket_total").data("ticketTotal",tiraje_amount);
    total_cal();



    jQuery('.col').click(function() {


        if(jQuery(this).attr("data-num-tiraj")){


            tiraje_amount = jQuery(this).attr("data-num-tiraj");  // количество тиражей
            jQuery(this).parent().find(".col").css("background","white");
            jQuery(this).parent().find(".col").css("color","#8a8b8d");

            set_colors_clicked(this);
            jQuery("#col_ticket_total").text(tiraje_amount);
            jQuery("#ticket_amount").text(tiraje_amount);
            jQuery("#col_ticket_total").data("ticketTotal",tiraje_amount);

            total_cal();

        }


        if(jQuery(this).attr("data-num")){


          tiraje_tickets = jQuery(this).attr("data-num");  // количество билетов
            jQuery(this).parent().find(".col").css("background","white");
            jQuery(this).parent().find(".col").css("color","#8a8b8d");


            set_colors_clicked(this);
            tiraje_tickets_show(tiraje_tickets);


        }

    });



    function tiraje_tickets_show(tiraje_tickets){
        jQuery(".ticket_boxes").hide();

        for (i = 1; i <= tiraje_tickets; i++) {

        jQuery("#ticket_boxes_"+i).show();

         }
    }

    tiraje_tickets_show(tiraje_tickets);


    function total_cal(){


       var tirajTotal = jQuery("#col_tiraj_total").data("tirajTotal");
        var ticketTotal = jQuery("#col_ticket_total").data("ticketTotal");
        var ticketPrice= jQuery("#col_ticket_price").data("ticketPrice");

        console.log(tirajTotal ,ticketTotal, ticketPrice );
        var total_cal = tirajTotal*ticketTotal*ticketPrice;

        jQuery("#cal_total").text(total_cal);

        return total_cal;
    }

    function set_colors_clicked(obj){


        jQuery(obj).css("background","#8a8b8d");
        jQuery(obj).css("color","white");



    }




    jQuery('.confirm').click(function() {

        var generate = jQuery(this).parent().find(".generate");
        var ticket_number =  jQuery(this).parent().data("tnumber");



        var ball = jQuery(this).parent().find(".main .ball");
        var k = 1;
        var selected_numbers = {};
        jQuery.each(ball, function (index, value) {

            if (jQuery(value).css("color") == 'rgb(255, 255, 255)') {


               selected_numbers[k] = jQuery(value).text();
                k++;
            }
        });

        tickets[ticket_number] =selected_numbers;

        var add_ball = jQuery(this).parent().find(".additional .ball");

        var g = 1;
        var add_selected_numbers = {};
        jQuery.each(add_ball, function (index, value) {

            if (jQuery(value).css("color") == 'rgb(255, 255, 255)') {


                add_selected_numbers[g] = jQuery(value).text();
                g++;
            }
        });
       additional_fields_tickets[ticket_number]=add_selected_numbers;

        if (k <= 6) {

        alert("Пожалуйста выбирете  6 номеров");

        }else{
            generate.css('opacity','0');
            generate.attr('disabled');
           var tirajTotal = Object.keys(tickets).length;
            var ticket_tiraje = jQuery("#col_ticket_total").data("ticketTotal");
            var ticket_tiraje_number = jQuery("#ticket_tiraje_number").data("tirajeNumber");

            set_ticket_numbers(tickets,  additional_fields_tickets, ticket_tiraje, ticket_tiraje_number);
            localStorage.setItem('tirajTotalTickets', JSON.stringify(tickets));
            set_tiraje_total(tirajTotal);
            total_cal();

        }




    });

    function set_tiraje_total(tirajTotal){

        jQuery("#col_tiraj_total").data("tirajTotal", tirajTotal);
        jQuery("#col_tiraj_total").text(tirajTotal);
        jQuery("#tiiraje_amount").text(tirajTotal);
        jQuery("#tiiraje_amount").data("tiirajeAmount", tirajTotal);


      //  localStorage.setItem('tirajTotal', tirajTotal);
    }

    function set_ticket_numbers(tiket_numbers, additional_fields_tickets, ticket_tiraje, ticket_tiraje_number){

        console.log(additional_fields_tickets );

        jQuery.ajax({
            method: "POST",
            url: "index.php?option=com_lotto&view=ajax&format=ajax",
            data: {tickets_numbers: tiket_numbers,  additional_fields_tickets: additional_fields_tickets, ticket_tiraje: ticket_tiraje, ticket_tiraje_number: ticket_tiraje_number }
        })
            .done(function( msg ) {

                console.log("Data Saved: " + msg);
            });

    }

    jQuery('#pay').click(function() {

        url = "index.php?option=com_lotto&view=cart";
        jQuery(location).attr("href", url);


    });


});