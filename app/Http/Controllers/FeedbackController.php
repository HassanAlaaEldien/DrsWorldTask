<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Requests\FeedbackRequest;
use App\Http\Responses\ResponsesInterface;
use App\Jobs\SaveFeedbackToDB;
use App\Search\FeedbackRepository;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{
    /**
     * @var ResponsesInterface
     */
    private $apiResponder;
    private $searchRepository;

    /**
     * FeedbackController constructor.
     * @param ResponsesInterface $apiResponder
     * @param FeedbackRepository $searchRepository
     */
    public function __construct(ResponsesInterface $apiResponder, FeedbackRepository $searchRepository)
    {
        $this->apiResponder = $apiResponder;
        $this->searchRepository = $searchRepository;
        $this->middleware('company.token.validity')->except('store');
    }

    /**
     * @param FeedbackRequest $request
     * @return JsonResponse
     */
    public function store(FeedbackRequest $request): JsonResponse
    {
        // at first we have to calculate feedback number and cache it.
        $number = Feedback::CacheTotalCount($request->company_token);

        // then we will dispatch our feedback creation job.
        $this->dispatch(new SaveFeedbackToDB($request->all()));

        return $this->apiResponder->respond(compact('number'));
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // At first we have fetch all feedbacks using elasticsearch if enabled or using eloquent query.
        $feedback = $this->searchRepository->search(
            request()->header('Company-Token'), new Feedback()
        );

        return $this->apiResponder->respond(['data' => $feedback]);
    }

    /**
     * Handle the request for showing feedback using number and token.
     *
     * @param Feedback $number
     * @return JsonResponse
     */
    public function show(Feedback $number): JsonResponse
    {
        return $this->apiResponder->respond(['data' => $number]);
    }

    /**
     * Handle the request for returning total number of specific feedback.
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        $count = Feedback::CompanyTotalCount(request()->header('Company-Token'));

        return $this->apiResponder->respond(compact('count'));
    }
}
