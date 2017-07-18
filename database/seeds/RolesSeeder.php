<?php

use App\Role;
use App\Permission;
use App\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vanilla User Perms

        $votePermission = new Permission();
        $votePermission->name = 'vote';
        $votePermission->display_name = 'Vote';
        $votePermission->save();

        $commentPermission = new Permission();
        $commentPermission->name = 'comment';
        $commentPermission->display_name = 'Comment';
        $commentPermission->save();

        $editOwnCommentsPermission = new Permission();
        $editOwnCommentsPermission->name = 'editOwnComments';
        $editOwnCommentsPermission->display_name = 'Edit Own Comments';
        $editOwnCommentsPermission->save();

        $deleteOwnCommentsPermission = new Permission();
        $deleteOwnCommentsPermission->name = 'deleteOwnComments';
        $deleteOwnCommentsPermission->display_name = 'Delete Own Comments';
        $deleteOwnCommentsPermission->save();

        $postPropositionsPermission = new Permission();
        $postPropositionsPermission->name = 'postPropositions';
        $postPropositionsPermission->display_name = 'Post Propositions';
        $postPropositionsPermission->save();

        $deleteOwnPropositionsPermission = new Permission();
        $deleteOwnPropositionsPermission->name = 'deleteOwnPropositions';
        $deleteOwnPropositionsPermission->display_name = 'Delete Own Propositions';
        $deleteOwnPropositionsPermission->save();

        $flagCommentsPermission = new Permission();
        $flagCommentsPermission->name = 'flagComments';
        $flagCommentsPermission->display_name = 'Flag Comments';
        $flagCommentsPermission->save();


        // Moderator permissions

        $approveOrBlockPropositionsPermission = new Permission();
        $approveOrBlockPropositionsPermission->name = 'approveOrBlockPropositions';
        $approveOrBlockPropositionsPermission->display_name = 'Approve Or Block Propositions';
        $approveOrBlockPropositionsPermission->save();

        $deleteCommentsPermission = new Permission();
        $deleteCommentsPermission->name = 'deleteComments';
        $deleteCommentsPermission->display_name = 'Can Delete Other Users\' Comments';
        $deleteCommentsPermission->save();

        $setPropositionMarkersPermission = new Permission();
        $setPropositionMarkersPermission->name = 'setPropositionMarkers';
        $setPropositionMarkersPermission->display_name = 'Set Proposition Markers';
        $setPropositionMarkersPermission->save();

        $distinguishSameRoleCommentsPermission = new Permission();
        $distinguishSameRoleCommentsPermission->name = 'distinguishSameRoleComments';
        $distinguishSameRoleCommentsPermission->display_name = 'Distinguish comments with the same role as commenter';
        $distinguishSameRoleCommentsPermission->save();

        $distinguishAllCommentsPermission = new Permission();
        $distinguishAllCommentsPermission->name = 'distinguishAllComments';
        $distinguishAllCommentsPermission->display_name = 'Distinguish any comments';
        $distinguishAllCommentsPermission->save();

        forAllTenants(function($tenant) use ($votePermission, $commentPermission, $flagCommentsPermission, $editOwnCommentsPermission, $deleteOwnCommentsPermission, $postPropositionsPermission, $deleteOwnPropositionsPermission, $approveOrBlockPropositionsPermission, $deleteCommentsPermission, $setPropositionMarkersPermission, $distinguishAllCommentsPermission, $distinguishSameRoleCommentsPermission) {
            $userRole = new Role();
            $userRole->name = 'user';
            $userRole->display_name = 'User';
            $userRole->description = 'A regular user.';
            $userRole->tenant_id = $tenant->id;
            $userRole->save();

            $modRole = new Role();
            $modRole->name = 'moderator';
            $modRole->display_name = 'Moderator';
            $modRole->tenant_id = $tenant->id;
            $modRole->save();

            $pcMemberRole = new Role();
            $pcMemberRole->name = 'pc_member';
            $pcMemberRole->display_name = 'Pupils\' Committee Member';
            $pcMemberRole->tenant_id = $tenant->id;
            $pcMemberRole->save();

            $userRole->attachPermissions([$votePermission, $commentPermission, $flagCommentsPermission, $editOwnCommentsPermission, $deleteOwnCommentsPermission, $postPropositionsPermission, $deleteOwnPropositionsPermission]);

            $modRole->attachPermissions([$approveOrBlockPropositionsPermission, $deleteCommentsPermission, $setPropositionMarkersPermission, $distinguishAllCommentsPermission]);

            $pcMemberRole->attachPermissions([$setPropositionMarkersPermission, $distinguishSameRoleCommentsPermission]);
        });
    }
}
