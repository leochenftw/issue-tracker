<?php use SaltedHerring\Debugger as Debugger;

class IssueForm extends Form {

	public function __construct($controller) {
		$fields = new FieldList(array(
            HiddenField::create('ProjectID', 'ProjectID', $controller->ID),
            OptionsetField::create('TypeID', 'Issue type', IssueType::get()->map('ID', 'Title'), IssueType::get()->first()->ID),
            DropdownField::create('Priority', 'Priority', Config::inst()->get('Issue', 'priorities')),
            TextField::create('Title', 'Summary')->setAttribute('placeholder', 'A short sentence summarising the issue'),
            TextareaField::create('Description', 'Details')->setAttribute('placeholder', 'e.g. the aspect of the issue, and how did you trigger the issue, etc.'),
            TextField::create('IssuePageURL', 'Issue found on page'),
            $uploader = UploadField::create('Screenshots', 'Screenshots'),
            ToggleCompositeField::create('Advanced', 'More options', array(
                DropdownField::create(
                    'OS',
                    'Operating System',
                    Config::inst()->get('Issue', 'OS')
                )->setEmptyString('- I don\'t know -'),
                TextField::create('ScreenWidth', 'Browser screen width'),
                TextField::create('ScreenHeight', 'Browser screen height')
            ))
        ));

        $uploader->setFolderName('screenshots/project-' . $controller->ID)
				->setCanAttachExisting(false)
				->setAllowedMaxFileNumber(10)
				->setAllowedExtensions(array('jpg', 'jpeg', 'png'))
				->setPreviewMaxWidth(300)
				->setPreviewMaxHeight(180)
				->setCanPreviewFolder(false)
				->setAutoUpload(false)
				->setFieldHolderTemplate('FrontendUploadField');

        if ($member = Member::currentUser()) {
            $fields->push(HiddenField::create('ReporterID', 'ReporterID', $member->ReporterProfileID));
            $fields->push(ReadonlyField::create('Reporter', 'You are reporting as:', $member->FirstName . ' ' . $member->Surname));
        } else {
            $fields->push(EmailField::create('Email', 'Your Email'));
        }

		$actions = new FieldList(
			$btnSubmit = FormAction::create('CreateIssue','Create')
		);

		$required_fields = array(
            'ProjectID',
            'Title'
		);

        if (empty($member)) {
            $required_fields[] = 'Email';
        }

		$required = new RequiredFields($required_fields);

		parent::__construct($controller, 'IssueForm', $fields, $actions, $required);
		$this->setFormMethod('POST', true)
			 ->setFormAction(Controller::join_links(BASE_URL, $controller->Link(), "IssueForm"));
	}

    public function validate() {
        $result = parent::validate();
        $data = $this->getData();
        $member = Member::currentUser();
        $errors = $this->validator->validate();
        $project = Versioned::get_by_stage('ProjectPage', 'Live')->byID($data['ProjectID']);
        if (!$project->canAcceptIssue()) {
            $this->addErrorMessage('Create', 'Project no longer accepts issue submissions', "bad");
            return false;
        }
        if (empty($member)) {
            if (!empty($data['Email'])) {
                if ($reporter = Reporter::get()->filter('Email', $data['Email'])->first()) {
                    $member = $reporter->Member();
                }
            }
        }
        if (empty($member)) {
            $this->addErrorMessage('Email', 'You don\'t have an account here', "bad");
            return false;
        }

        if (!$project->ProjectAdministrators()->byID($member->ID)
            && !$project->Develoeprs()->byID($member->ID)
            && !$project->ProjectManagers()->byID($member->ID)
            && !$project->Customers()->byID($member->ID)) {

            $this->addErrorMessage('Email', 'You have\'t been invited to this project, hence can\'t create issue', "bad");
            return false;
        }

        return $result;
    }

	public function CreateIssue($data, $form) {
        // Debugger::inspect($data);
		if ($data['SecurityID'] == Session::get('SecurityID')) {

            $issue = new Issue();
            $form->saveInto($issue);
            if (!empty($data['Email'])) {
                $reporter = Reporter::get()->filter('Email', $data['Email'])->first();
                $issue->ReporterID = $reporter->ID;
            }

            $issue->write();
			return Controller::curr()->redirectBack();
		}

		return Controller::curr()->httpError(400, 'Session expired, go back and refresh');
	}
}
