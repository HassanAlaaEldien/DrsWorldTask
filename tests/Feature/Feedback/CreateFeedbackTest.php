<?php

namespace Tests\Feature\Feedback;

use App\Feedback;
use App\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFeedbackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A data provider for the required fields to successfully create a feedback.
     *
     * @return array
     */
    public function requiredFields()
    {
        return [
            ['company_token'],
            ['priority'],
            ['device'],
            ['os'],
            ['memory'],
            ['storage'],
        ];
    }

    /**
     * @test
     */
    public function any_company_can_create_feedback()
    {
        $feedback = factory(Feedback::class)->make()->toArray();

        $state = factory(State::class)->make()->toArray();

        $response = $this->post(route('feedback.store'), $feedback + $state);

        $response->assertStatus(200);

        $response->assertExactJson(['number' => 1]);
    }

    /**
     * @test
     *
     * @dataProvider requiredFields
     *
     * @param $field
     */
    public function any_company_cannot_create_feedback_without_sending_required_attributes($field)
    {
        $feedback = factory(Feedback::class)->make([$field => null])->toArray();

        $state = factory(State::class)->make([$field => null])->toArray();

        $response = $this->post(route('feedback.store'), $feedback + $state);

        $this->assertValidationError($response, $field);
    }

    /**
     * @test
     */
    public function any_company_cannot_create_feedback_without_sending_valid_priority()
    {
        $feedback = factory(Feedback::class)->make(['priority' => 'x'])->toArray();

        $state = factory(State::class)->make()->toArray();

        $response = $this->post(route('feedback.store'), $feedback + $state);

        $this->assertValidationError($response, 'priority');
    }
}
