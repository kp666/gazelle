<?php

$forum = new \Gazelle\Forum();
$toc = $forum->tableOfContents();
$LastRead = Forums::get_last_read($Forums);

View::show_header('Forums');
?>
<div class="thin">
    <h2>Forums</h2>
    <div class="forum_list">
<?php
foreach ($toc as $category => $forumList) {
    $seen = 0;
    foreach ($forumList as $forum) {
        if (!Forums::check_forumperm($forum['ID'])) {
            continue;
        }
        if ($forum['ID'] == DONOR_FORUM) {
            $forum['Description'] = donorForumDescription();
        }
        $lastPostId = $LastRead[$forum['LastPostTopicID']] ?? 0;
        echo G::$Twig->render('forum/main.twig', [
            'creator'   => $forum['MinClassCreate'] <= $LoggedUser['Class'],
            'category'  => $category,
            'cut_title' => Format::cut_string($forum['Title'], 50, 1),
            'forum'     => $forum,
            'is_read'   => Forums::is_unread($forum['IsLocked'], $forum['IsSticky'], $forum['LastPostID'], $LastRead, $forum['LastPostTopicID'], $forum['LastPostTime'])
                ? 'unread' : 'read',
            'last_read' => $lastPostId,
            'page'      => $lastPostId ? $LastRead[$forum['LastPostID']]['Page'] : 0,
            'post_id'   => $lastPostId ? $LastRead[$forum['LastPostID']]['PostID'] : 0,
            'seen'      => ++$seen, // $seen == 1 implies <table> needs to be emitted
            'tool_tip'  => $forum['ID'] == DONOR_FORUM ? 'tooltip_gold' : 'tooltip',
            'user'      => Users::format_username($forum['LastPostAuthorID'], false, false, false),
            'user_time' => time_diff($forum['LastPostTime'], 1),
        ]);
    }
    /* close the <table> opened in first call to render() above */
    if ($seen) { ?>
        </table>
<?php
    }
} /* foreach */
?>
    </div>
    <div class="linkbox"><a href="forums.php?action=catchup&amp;forumid=all&amp;auth=<?=$LoggedUser['AuthKey']?>" class="brackets">Catch up</a></div>
</div>
<?php
View::show_footer();

function donorForumDescription() {
    $description = [
        "I want only two houses, rather than seven... I feel like letting go of things",
        "A billion here, a billion there, sooner or later it adds up to real money.",
        "I've cut back, because I'm buying a house in the West Village.",
        "Some girls are just born with glitter in their veins.",
        "I get half a million just to show up at parties. My life is, like, really, really fun.",
        "Some people change when they think they're a star or something",
        "I'd rather not talk about money. It’s kind of gross.",
        "I have not been to my house in Bermuda for two or three years, and the same goes for my house in Portofino. How long do I have to keep leading this life of sacrifice?",
        "When I see someone who is making anywhere from $300,000 to $750,000 a year, that's middle class.",
        "Money doesn't make you happy. I now have $50 million but I was just as happy when I had $48 million.",
        "I'd rather smoke crack than eat cheese from a tin.",
        "I am who I am. I can’t pretend to be somebody who makes $25,000 a year.",
        "A girl never knows when she might need a couple of diamonds at ten 'o' clock in the morning.",
        "I wouldn't run for president. I wouldn't want to move to a smaller house.",
        "I have the stardom glow.",
        "What's Walmart? Do they like, sell wall stuff?",
        "Whenever I watch TV and see those poor starving kids all over the world, I can't help but cry. I mean I'd love to be skinny like that, but not with all those flies and death and stuff.",
        "Too much money ain't enough money.",
        "What's a soup kitchen?",
        "I work very hard and I’m worth every cent!",
        "To all my Barbies out there who date Benjamin Franklin, George Washington, Abraham Lincoln, you'll be better off in life. Get that money.",
    ];
    return $description[rand(0, count($description) - 1)];
};
