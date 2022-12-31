<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAuthAPIRequest;
use App\Http\Requests\API\UpdateUserAuthAPIRequest;
use App\Models\User;
use App\Repositories\UserAuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class UserAuthAPIController
 */
class UserAuthAPIController extends AppBaseController
{
    private UserAuthRepository $userAuthRepository;

    public function __construct(UserAuthRepository $userAuthRepo)
    {
        $this->userAuthRepository = $userAuthRepo;
    }

    /**
     * Display a listing of the UserAuths.
     * GET|HEAD /user-auths
     */
    public function index(Request $request): JsonResponse
    {
        $userAuths = $this->userAuthRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($userAuths->toArray(), 'User Auths retrieved successfully');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(CreateUserAuthAPIRequest $request)
    {
        $response = $this->userAuthRepository->registerUser($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    public function login(Request $request)
    {
        $response = $this->userAuthRepository->loginUser($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Store a newly created UserAuth in storage.
     * POST /user-auths
     */
    public function store(CreateUserAuthAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $userAuth = $this->userAuthRepository->create($input);

        return $this->sendResponse($userAuth->toArray(), 'User Auth saved successfully');
    }

    /**
     * Display the specified UserAuth.
     * GET|HEAD /user-auths/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var User $userAuth */
        $userAuth = $this->userAuthRepository->find($id);

        if (empty($userAuth)) {
            return $this->sendError('User Auth not found');
        }

        return $this->sendResponse($userAuth->toArray(), 'User Auth retrieved successfully');
    }

    /**
     * Update the specified UserAuth in storage.
     * PUT/PATCH /user-auths/{id}
     */
    public function update(UpdateUserAuthAPIRequest $request): JsonResponse
    {
        $response = $this->userAuthRepository->updateUser($request);

        if ($response['status']) {
            return $this->sendResponse($response['data'], $response['response_message']);
        } else {
            return $this->sendError($response['response_message'], $response['data']);
        }
    }

    /**
     * Remove the specified UserAuth from storage.
     * DELETE /user-auths/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var User $userAuth */
        $userAuth = $this->userAuthRepository->find($id);

        if (empty($userAuth)) {
            return $this->sendError('User Auth not found');
        }

        $userAuth->delete();

        return $this->sendSuccess('User Auth deleted successfully');
    }
}
