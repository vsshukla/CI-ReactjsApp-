<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/validators/StudentFormValidator.php';
use Restserver\Libraries\REST_Controller;

/**
 * Student controller
 *
 * Class Student
 */
class StudentController extends REST_Controller
{
    /**
     * Student constructor
     */
    public function __construct()
    {
        parent::__construct();
        // $this->load->service('student_service', '', true);
        $this->load->model('student_model');
        $this->load->library('form_validation');
    }

    public function addStudent_post()
    {
        $validator = new StudentFormValidator;
        $validator->studentValidation();
        
        if ($this->form_validation->run() === false) {
            $this->response([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_EXPECTATION_FAILED);
        } else {
            $data = $this->input->post(null, true);
            $result = $this->student_model->addStudentInfo($data);

            if (!empty($result)) {
                $this->response([
                    'status' => true,
                    'message' => 'Data Added sucessfully',
                    'data' => ''
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'fail to save data',
                    'data' => $data
                ], REST_Controller::HTTP_EXPECTATION_FAILED);
            }
        }
    }

    public function student_get()
    {
        $studentDetail = $this->student_model->getAllMember();
        if (empty($studentDetail)) {
            $this->response([
                'status' => true,
                'message' => 'User not found',
                'data' => ''
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
        $this->response([
                'status' => true,
                'message' => 'List of available users',
                'data' => $studentDetail
            ], REST_Controller::HTTP_OK);
    }
}
