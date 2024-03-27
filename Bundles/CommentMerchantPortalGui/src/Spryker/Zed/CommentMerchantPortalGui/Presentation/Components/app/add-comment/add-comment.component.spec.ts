import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { of } from 'rxjs';
import { CommentsConfiguratorService } from '../../services/comments-configurator';
import { AddCommentComponent } from './add-comment.component';

const mockAddComment = {
    crf: 'mockCrf',
    ownerId: 'mockOwnerId',
    ownerType: 'mockOwnerType',
};

const mockCommentsConfiguratorService = {
    getAccomplishing: jest.fn().mockReturnValue(of(null)),
    getError: jest.fn().mockReturnValue(of(null)),
    commentAction: jest.fn(),
};

describe('AddCommentComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(AddCommentComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
            providers: [
                {
                    provide: CommentsConfiguratorService,
                    useValue: mockCommentsConfiguratorService,
                },
            ],
        });
    });

    it('should render <spy-textarea> component', async () => {
        const host = await createComponentWrapper(createComponent, { addComment: mockAddComment });
        const textAreaComponent = host.queryCss('spy-textarea');

        expect(textAreaComponent).toBeTruthy();
    });

    it('should render meta inputs with proper values', async () => {
        const host = await createComponentWrapper(createComponent, { addComment: mockAddComment });
        const crfInput = host.queryCss('input[name=_token]');
        const idInput = host.queryCss('input[name=ownerId]');
        const typeInput = host.queryCss('input[name=ownerType]');

        expect(crfInput.properties.value).toBe(mockAddComment.crf);
        expect(idInput.properties.value).toBe(mockAddComment.ownerId);
        expect(typeInput.properties.value).toBe(mockAddComment.ownerType);
    });

    it('should trigger CommentsConfiguratorService.commentAction', async () => {
        const host = await createComponentWrapper(createComponent, { addComment: mockAddComment });
        const buttonComponent = host.queryCss('spy-button');

        buttonComponent.nativeElement.click();
        host.detectChanges();

        expect(mockCommentsConfiguratorService.commentAction).toHaveBeenCalled();
    });
});
