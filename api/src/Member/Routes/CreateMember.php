<?php

namespace App\Member\Routes;

use App\Auth\Support\GenerateJWT;
use App\Member\Models\Member;
use App\Member\Requests\CreateMemberRequest;
use App\User\Models\User;
use Neoan\Response\Response;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;

#[Post('/api/member')]
class CreateMember implements Routable
{
    /**
     * @throws \Exception
     */
    public function __invoke(CreateMemberRequest $request): array
    {
        $user = new User();
        try{
            $user->email = $request->email;
            $user->password = $request->password;
            $user->store();
        } catch (\Exception $e){
            Response::setStatusCode(422);
            return [
                'error' => 'Wrong format or user exists'
            ];
        }
        $member = new Member();
        $member->userId = $user->id;
        $member->firstName = $request->firstName;
        $member->lastName = $request->lastName;
        $member->phone = $request->phone;
        $member->dateOfBirth = $request->dateOfBirth;
        try{
            $member->store();
        }catch (\Exception $e){
            Response::setStatusCode(422);
            return [
                'error' => 'Error creating member'
            ];
        }
        $login = User::login($request->email, $request->password);
        return GenerateJWT::generate($login);
    }
}