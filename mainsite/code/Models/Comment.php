<?php

class Comment extends DataObject {
    private static $db = array(
        'Content'           =>  'Text',
        'isInternal'        =>  'Boolean'
    );

    private static $has_one = array(
        'Member'            =>  'Member',
        'inIssue'           =>  'Issue',
        'ReplyToComment'    =>  'Comment'
    );
}
