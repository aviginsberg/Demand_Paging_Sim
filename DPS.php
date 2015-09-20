<?php

/**
 * Author: Avi Ginsberg
 * Course: CS438
 * IDE: PhpStorm.
 * Date: 9/18/15
 */
class DPS
{

    //Set up our vars
    protected $page_size, $total_mem_size, $program_total_words;
    protected $Main_Memory, $word_request_sequence, $page_fault_count;



    //construct the instance with params
    public function __construct($page_size, $total_mem_size, $program_total_words, $word_request_sequence ) {
        $this->word_request_sequence = Array(); 
        $this->Main_Memory = Array();
        $this->page_size = $page_size;
        $this->total_mem_size = $total_mem_size;
        $this->program_total_words = $program_total_words;
        $this->word_request_sequence = $word_request_sequence;

    }

    public function log($msg){
        echo "\n$msg";
    }

    public function get_page_num_by_word_num($word_num){
        return floor($word_num/$this->page_size);
    }

    //Returns the total number of pages in the program
    public function get_total_num_of_pages(){
        return floor($this->program_total_words/$this->page_size);

    }

    //Returns the total number of page frames in the MM
    public function get_total_page_frames(){
        //note the use of floor here is unnecessary but if the page frame size didn't divide into MM evenly it would be important
        return floor($this->total_mem_size/$this->page_size);
    }

    //Returns TRUE if MM is full, FALSE if not full
    public function is_MM_full(){
        return !(count($this->Main_Memory) < $this->get_total_page_frames());
    }

    //Returns TRUE if there is a page fault (page not found in main memory), FALSE if there is not a page fault (page IS in main memory)
    public function check_for_page_fault($page_num){
        if(!in_array($page_num, $this->Main_Memory)){
            $this->page_fault_count++;
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //Add the page to MM using First In First Out. Assumes you have already checked if the page is in MM and hence does NOT prevent duplicate pages. Should only be called when there is a page fault.
    public function add_page_to_MM_FIFO($page){
        //MM is NOT full: push the item into the array
        if (!$this->is_MM_full()){
            array_unshift($this->Main_Memory, $page);
            $this->log("MM not full. Adding page $page to MM.");
        }else{
            //MM is full: shift off the oldest item and add in the new item
            array_pop($this->Main_Memory);
            array_unshift($this->Main_Memory, $page);
            $this->log("MM is full. Removing oldest page and adding page $page to MM.");
        }

    }


    public function print_MM_snapshot(){
        $this->log("----------MM Snapshot----------");
    foreach ($this->Main_Memory as $MMentrynum => $MMentry){
        $this->log("Memory Page $MMentrynum - Program Page $MMentry");
    }
        $this->log("-------------------------------");
    }



    public function do_sim(){
        $this->log("Simulation started with the following settings:");
        $this->log("MM size: ". $this->total_mem_size);
        $this->log("Page size: ". $this->page_size."\n\n");

     //for each word request in the request sequence

    foreach($this->word_request_sequence as $word_req_key => $word_req_value){
        //get page number of word
        $page_needed = $this->get_page_num_by_word_num($word_req_value);
        $this->log("Current word being requested is $word_req_value. This is on program page $page_needed.");
        //determine if page is in MM
            //if page is in MM, print out current MM snapshot and report no page fault
        if(!$this->check_for_page_fault($page_needed)){
            $this->log("No page fault detected.\nPrinting snapshot of MM.");
            $this->print_MM_snapshot();
        }else{
            //if page is NOT in MM, PAGE FAULT OCCURED
                //remove oldest page from MM, load new page, print out current MM snapshot, report page fault
            $this->log("*A page fault occured!*");
            $this->add_page_to_MM_FIFO($page_needed);
            $this->log("Printing snapshot of MM");
            $this->print_MM_snapshot();

        }
        $this->log("\n\n\n");
    }

        $this->log("Simulation finished!\nStats & Info:");
        $this->log("MM size: ". $this->total_mem_size);
        $this->log("Page size: ". $this->page_size);
        $this->log("Page frames in MM: ".$this->get_total_page_frames());
        $this->log("Total number of words in program: ".$this->program_total_words);
        $this->log("Number of word requests made: ".count($this->word_request_sequence));
        $this->log("Number of page faults that occured: ".$this->page_fault_count);
        $this->log("Success rate: ".round((100*(1-($this->page_fault_count/count($this->word_request_sequence)))),3)."%");
        //(1- Failure-Rate = page faults / Page_Requests_Made)*100


    }

}