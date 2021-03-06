<?php

namespace SilverStripe\Security\MemberAuthenticator;

use SilverStripe\ORM\ValidationResult;
use SilverStripe\Security\Authenticator as BaseAuthenticator;
use SilverStripe\Security\Member;

class CMSMemberAuthenticator extends MemberAuthenticator
{

    public function supportedServices()
    {
        return BaseAuthenticator::CMS_LOGIN;
    }

    /**
     * @param array $data
     * @param ValidationResult|null $result
     * @param Member|null $member
     * @return Member
     */
    protected function authenticateMember($data, &$result = null, $member = null)
    {
        // Attempt to identify by temporary ID
        if (!empty($data['tempid'])) {
            // Find user by tempid, in case they are re-validating an existing session
            $member = Member::member_from_tempid($data['tempid']);
            if ($member) {
                $data['email'] = $member->Email;
            }
        }

        return parent::authenticateMember($data, $result, $member);
    }

    /**
     * @param string $link
     * @return CMSLoginHandler
     */
    public function getLoginHandler($link)
    {
        return CMSLoginHandler::create($link, $this);
    }
}
