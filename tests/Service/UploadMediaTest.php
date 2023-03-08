<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadMediaTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/']);
    }

    public function testCreateAMediaObject(): void
    {
        $file = new UploadedFile('fixtures/files/image.png', 'image.png');
        $client = self::createClient();

        $client->request('POST', '/media_objects', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                // If you have additional fields in your MediaObject entity, use the parameters.
                'filename' => "image",
                'data' => "/9j/2wCEAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDIBCQkJDAsMGA0NGDIhHCEyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMv/AABEIAGQAZAMBIgACEQEDEQH/xAGiAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgsQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+gEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoLEQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/AOWe1kLrtkOAasFVBxipyNpBpTGGwcc1VjJjbdNxBIpbq+s9OXNzPHGSMhSeT9BSz3MFjE007qiKOp7n0rzS5nku7qSZ2ZmdicscmjYIwvuaeoeJr+7kYQymCLssfBI9zWRl2JcsS3UnPNPjhLVO8KohApGqVifT9UvbJw8M7bc52vyDXZ6Vq6arGRtVJk+9Hn9R7VxG1VG0dM4pbW6ksruG4jOGRufegTjc9JKB25Hbmq0MkixsPKYgE4NS6PfRX0RmTnPBX0NaDKSCuODVJGdjHSZppygjIwM5NSMuBkn2xVh4thbHU1HHFliD0pjIVgwOBxTvJPoauKo28Uu2gCuVyKFQkjHQUinZxyRin2sqzMzYK9sGgDlvGchEVtDkgHLY7H/P9a5iKEnaFUs56AV2njKBW06GfjckoUfQj/61ZOg2wcPIRz0+grKrPkVzejD2klEqR6VcyABVAz29KdLol8oUFC2eMrzXd6ZYiTkKMVtx6YSvKdOcYrzpY2Seh6f1KnbVnljeG7/bubjNZ91plzApJGcdcV67daawjJCYIrmNStMb8rnIweKdPGSb1CeDhb3TD8D3B/tSS3Lkb4yVXsSK9CWPIyTXmXh2QWni22zwGdkP4givTlLD5sZBr1Iu6PHmrMrTw85qBRgk1oSsm3JIGRxULw5UGmSQheKMVJtxxRigRSYDgBuSasJCmNpIz61SkZBg5O7tirSODsYnBIoKMrxPbmTQLkEg+WQ6n6Gszw/GI7cM54YZ/Cug1ONbnS5U4bvjHvWXpNgsmnRedIYoVG12U4PHFcmKelmduDVnzHYaIjMgZYyV+ldCFfH3D06VwNvcWtk8C2Gt6hH5rYiHkb1fB2/lniu60u+mmURTOrShd24DGR6150qXLqegqvORXcczRHEJI964rVJVSd4nwGb0Oa3PE90JUPmT3YhQ8panluQP6iucMWk3McMUVjdQSyjckszlmPPc9AfanClG3MJ1ZJ8qRzEUSW/iq0kYMUD722jJr0wqFQAYrjP7P2a+EJGY4xnPf5hXX4zx3r1aE+aJ5eJgoyv3IpYI5nCkcKcg5odHjAIO5fSnQkh2RiMnkGpP4QDWxzFYuM9DSb196lcDd2puF9qAM8FHf9KtywqdmB0qvd7V2uuAAcmpRdxyAbWB+lAwmj2WkqpgHaSD71V8ORLNbvbTEH5iG5681cd1K44I7is3TpDBqNyFHypIWwOw61yYyLcbnZg5JS5TuLXR4Y40VfLIXoWiBIH1qtEyf29sjxsSMoAOKWXXY4rZVDqhK53N/nk1T8Oarpkd9L9omxO/IMnc98V5qTkemrRLumpFcSTQNIyOjEowGeM9Klv9OjMZlmZpCvTI6VjT6vYDWWmtXZQDy+eCKsXmvpc2EphdX2jkg0NNaDdmzl5YBc680ikkxgEAd/m/+tXQCHaCWcmsnQ1LX943XAVT9eTXQNbLIvUg162HjywPGxU+aduxWtrZQxbJ4HWkFuS3LE7TU1pkPKrcYPAqfaCxxitzmuVTED2o8oegqRsqxFJk0DMWX95Cy+oqjZxmKRkJ6Dj3qVbmNhy2D70kUMk92qwqZHP3VQZJoGWF2SpnGDVISfYtUV2b91OpjJPY44rq9O8KXVxMVnPlE87V5I+tcx4i0lrTxNPZuWaJQDGD6EA5/Osq9uRqRth7+090l1G1S9tbeSOYmREG6PsNtauhaPp97CJZ5LeCYckSlwAfZga5uGZ7G/jEuSjDGT3rdtluIgRZ3wjiOPvRhgPpXAvd0ex6cWne+/8AXcm1/SrC0gENm9vKzcbkjbYB9Sefwrnprmy0qwngjfdO7LnI6AVs3Yfb5t/eiTYuVCqACa4zTgL/AFtppxvRWJVf77DoKunFVHboZ1qipq63Oy8PW8lvYebPxLO5lYHtnoPyroIhnmufivwYuco3o1a1tcpgfMM16CVlZHkybbuyHUoZUmSSPO0csB3qeJcRJuJG4dQatyDzYTjn2qGKImDa3Qcr7UxEbWxzxI1J9lb/AJ6NVvHAoxQFzn9K8OXN8fNuwbe3PQkfO30H9a9A0nRbSxiQW0Cxkj5n6sfqait7XCxg5J3ck1txjYnA7VSVhN3HWkarKSBwOK5bx9oZaS21aJM7P3UuPQ/dP55H4110S7VX86uBIrq3ktrhA8TqVZT3BrOrDni4mtGfs5qR4tc6fDeQbXXPoR1B9RWS1rqVgQIHWWIdAxwfbJrutd0GTRLvadzW0h/cy46/7Lf7Q/WsmSDK5BzXiOc6T5ZHtpQqJSRx08Go3rbZ3CR9CEOSR9arsotZYjCAohYMMexrqriEupVep4rFvdPaNCozkDrW1OtcznRR2eq6VG4iu4lAMignjj1rKciMESLjFdstm/8AYlkJF+YRIDn121lzabG5KuoINeueJezsYtrNkgo7hcetX1uAn35Rt6c1BcaW9sCYTlfSs26J8hdwyUYEigNzaDFxuRsj60uH9azokTZnJGecA0/an95/zoA9BRFGzjuas4GTUC/wfU1Y7mrEWVHyipY/lII7VGv3RUiVIy1NawX9s9tcxiSJ+CD/AD+teSTjyb2aJSSqSMgJ6kA4r2GHqPqK8evP+Qpc/wDXeT/0I15uYJWTPSwLd2iDaPNLd6W3t47rULWKVcq8qqfpmj+OpdP/AOQrZf8AXdP5159P4kehP4Wej3ka+VGuON1Yd3EozgdK3rz7if7xrEvOjV9GfOMzXAK4Nc1qqiOXKjrkGumb7o+tc1rH+sH40mCMb+0JlAA29PSk/tGf/Y/KqrdvpSUiz//Z",
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(MediaObject::class);
        $this->assertJsonContains([
            'title' => 'My file uploaded',
        ]);
    }
}
