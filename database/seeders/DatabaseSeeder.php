<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Organization;
use App\Models\Question;
use App\Models\Response;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create organizations
        $acme = Organization::create(['name' => 'Acme Corp']);
        $globex = Organization::create(['name' => 'Globex Industries']);
        $initech = Organization::create(['name' => 'Initech Solutions']);

        // Create users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'organization_id' => $acme->id,
            'role' => 'admin',
        ]);

        $users = collect();
        $users->push($admin);

        // Acme Corp users
        $users->push(User::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah@acme.com',
            'password' => 'password',
            'organization_id' => $acme->id,
            'role' => 'manager',
        ]));
        $users->push(User::create([
            'name' => 'James Wilson',
            'email' => 'james@acme.com',
            'password' => 'password',
            'organization_id' => $acme->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@acme.com',
            'password' => 'password',
            'organization_id' => $acme->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'David Kim',
            'email' => 'david@acme.com',
            'password' => 'password',
            'organization_id' => $acme->id,
            'role' => 'member',
        ]));

        // Globex Industries users
        $users->push(User::create([
            'name' => 'Emily Johnson',
            'email' => 'emily@globex.com',
            'password' => 'password',
            'organization_id' => $globex->id,
            'role' => 'manager',
        ]));
        $users->push(User::create([
            'name' => 'Michael Brown',
            'email' => 'michael@globex.com',
            'password' => 'password',
            'organization_id' => $globex->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'Lisa Wang',
            'email' => 'lisa@globex.com',
            'password' => 'password',
            'organization_id' => $globex->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'Robert Taylor',
            'email' => 'robert@globex.com',
            'password' => 'password',
            'organization_id' => $globex->id,
            'role' => 'member',
        ]));

        // Initech Solutions users
        $users->push(User::create([
            'name' => 'Jennifer Lee',
            'email' => 'jennifer@initech.com',
            'password' => 'password',
            'organization_id' => $initech->id,
            'role' => 'manager',
        ]));
        $users->push(User::create([
            'name' => 'Thomas Anderson',
            'email' => 'thomas@initech.com',
            'password' => 'password',
            'organization_id' => $initech->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'Rachel Green',
            'email' => 'rachel@initech.com',
            'password' => 'password',
            'organization_id' => $initech->id,
            'role' => 'member',
        ]));
        $users->push(User::create([
            'name' => 'Chris Martinez',
            'email' => 'chris@initech.com',
            'password' => 'password',
            'organization_id' => $initech->id,
            'role' => 'member',
        ]));

        $likertOptions = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];

        // Assessment 1: Leadership Effectiveness (active, 6 questions)
        $assessment1 = Assessment::create([
            'title' => 'Leadership Effectiveness',
            'description' => 'Evaluate leadership qualities and effectiveness within your team. This assessment covers communication, decision-making, and team development.',
            'organization_id' => $acme->id,
            'status' => 'active',
        ]);

        $q1_1 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'How would you rate your direct manager\'s communication skills?',
            'type' => 'number',
            'sort_order' => 1,
        ]);
        $q1_2 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'My manager clearly communicates team goals and expectations.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 2,
        ]);
        $q1_3 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'Describe a situation where your manager demonstrated strong leadership.',
            'type' => 'text',
            'sort_order' => 3,
        ]);
        $q1_4 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'How effectively does your manager handle conflict resolution?',
            'type' => 'number',
            'sort_order' => 4,
        ]);
        $q1_5 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'My manager provides regular and constructive feedback.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 5,
        ]);
        $q1_6 = Question::create([
            'assessment_id' => $assessment1->id,
            'text' => 'What is one area where your manager could improve?',
            'type' => 'text',
            'sort_order' => 6,
        ]);

        // Assessment 2: Team Dynamics Survey (active, 5 questions)
        $assessment2 = Assessment::create([
            'title' => 'Team Dynamics Survey',
            'description' => 'Assess how well your team collaborates, communicates, and works together toward shared objectives.',
            'organization_id' => $globex->id,
            'status' => 'active',
        ]);

        $q2_1 = Question::create([
            'assessment_id' => $assessment2->id,
            'text' => 'I feel comfortable sharing ideas and opinions with my team.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 1,
        ]);
        $q2_2 = Question::create([
            'assessment_id' => $assessment2->id,
            'text' => 'Rate your team\'s overall collaboration effectiveness.',
            'type' => 'number',
            'sort_order' => 2,
        ]);
        $q2_3 = Question::create([
            'assessment_id' => $assessment2->id,
            'text' => 'How well does your team handle disagreements?',
            'type' => 'number',
            'sort_order' => 3,
        ]);
        $q2_4 = Question::create([
            'assessment_id' => $assessment2->id,
            'text' => 'What is the biggest strength of your team?',
            'type' => 'text',
            'sort_order' => 4,
        ]);
        $q2_5 = Question::create([
            'assessment_id' => $assessment2->id,
            'text' => 'Team meetings are productive and well-organized.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 5,
        ]);

        // Assessment 3: Culture Audit (draft, 8 questions)
        $assessment3 = Assessment::create([
            'title' => 'Culture Audit',
            'description' => 'A comprehensive review of organizational culture, values alignment, and employee satisfaction across all departments.',
            'organization_id' => $initech->id,
            'status' => 'draft',
        ]);

        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'I understand and align with the company\'s core values.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 1,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'Rate your overall job satisfaction.',
            'type' => 'number',
            'sort_order' => 2,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'The company promotes a healthy work-life balance.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 3,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'How would you describe the company culture to a friend?',
            'type' => 'text',
            'sort_order' => 4,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'I feel recognized and valued for my contributions.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 5,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'Rate the effectiveness of internal communication.',
            'type' => 'number',
            'sort_order' => 6,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'The company provides adequate opportunities for professional growth.',
            'type' => 'choice',
            'options' => $likertOptions,
            'sort_order' => 7,
        ]);
        Question::create([
            'assessment_id' => $assessment3->id,
            'text' => 'What one change would most improve the company culture?',
            'type' => 'text',
            'sort_order' => 8,
        ]);

        // Seed responses for Assessment 1 (~60% completion)
        // 8 users respond, but not all complete every question
        $a1Answers = [
            // admin - all 6 questions
            [$q1_1->id => '8', $q1_2->id => 'Agree', $q1_3->id => 'During our product launch crisis, my manager stayed calm and delegated tasks effectively.', $q1_4->id => '7', $q1_5->id => 'Strongly Agree', $q1_6->id => 'Could improve on giving more timely feedback rather than saving it for quarterly reviews.'],
            // Sarah - all 6
            [$q1_1->id => '9', $q1_2->id => 'Strongly Agree', $q1_3->id => 'The team restructuring was handled with transparency and empathy.', $q1_4->id => '8', $q1_5->id => 'Agree', $q1_6->id => 'More one-on-one meetings would be helpful.'],
            // James - 5 questions (skip last)
            [$q1_1->id => '6', $q1_2->id => 'Neutral', $q1_3->id => 'Navigating budget cuts while keeping the team motivated.', $q1_4->id => '5', $q1_5->id => 'Disagree'],
            // Maria - all 6
            [$q1_1->id => '7', $q1_2->id => 'Agree', $q1_3->id => 'When a teammate was struggling, the manager provided mentoring and resources.', $q1_4->id => '8', $q1_5->id => 'Agree', $q1_6->id => 'Better delegation of responsibilities.'],
            // David - 4 questions
            [$q1_1->id => '8', $q1_2->id => 'Strongly Agree', $q1_3->id => 'Leading the cross-department collaboration initiative.', $q1_4->id => '7'],
            // Emily - all 6
            [$q1_1->id => '5', $q1_2->id => 'Neutral', $q1_3->id => 'Hard to think of a specific example.', $q1_4->id => '4', $q1_5->id => 'Neutral', $q1_6->id => 'More transparency in decision making.'],
            // Michael - 3 questions
            [$q1_1->id => '7', $q1_2->id => 'Agree', $q1_3->id => 'Handling the client escalation professionally.'],
            // Lisa - all 6
            [$q1_1->id => '9', $q1_2->id => 'Strongly Agree', $q1_3->id => 'My manager advocated for our team during the reorganization.', $q1_4->id => '9', $q1_5->id => 'Strongly Agree', $q1_6->id => 'Sometimes takes on too much instead of delegating.'],
        ];

        $a1Respondents = $users->slice(0, 8);
        foreach ($a1Respondents->values() as $index => $user) {
            if (isset($a1Answers[$index])) {
                foreach ($a1Answers[$index] as $questionId => $value) {
                    Response::create([
                        'question_id' => $questionId,
                        'user_id' => $user->id,
                        'assessment_id' => $assessment1->id,
                        'value' => $value,
                    ]);
                }
            }
        }

        // Seed responses for Assessment 2 (~40% completion)
        // 5 users respond, partial completion
        $a2Answers = [
            // Maria - all 5
            [$q2_1->id => 'Agree', $q2_2->id => '7', $q2_3->id => '6', $q2_4->id => 'Our diverse perspectives and willingness to help each other.', $q2_5->id => 'Agree'],
            // David - 3 questions
            [$q2_1->id => 'Strongly Agree', $q2_2->id => '8', $q2_3->id => '7'],
            // Emily - all 5
            [$q2_1->id => 'Neutral', $q2_2->id => '5', $q2_3->id => '4', $q2_4->id => 'We have strong technical skills but need better communication.', $q2_5->id => 'Disagree'],
            // Michael - 2 questions
            [$q2_1->id => 'Agree', $q2_2->id => '6'],
            // Lisa - 4 questions
            [$q2_1->id => 'Strongly Agree', $q2_2->id => '9', $q2_3->id => '8', $q2_4->id => 'Trust and mutual respect among team members.'],
        ];

        $a2Respondents = $users->slice(3, 5); // Maria, David, Emily, Michael, Lisa
        foreach ($a2Respondents->values() as $index => $user) {
            if (isset($a2Answers[$index])) {
                foreach ($a2Answers[$index] as $questionId => $value) {
                    Response::create([
                        'question_id' => $questionId,
                        'user_id' => $user->id,
                        'assessment_id' => $assessment2->id,
                        'value' => $value,
                    ]);
                }
            }
        }

        // Assessment 3 has no responses (it's a draft)
    }
}
