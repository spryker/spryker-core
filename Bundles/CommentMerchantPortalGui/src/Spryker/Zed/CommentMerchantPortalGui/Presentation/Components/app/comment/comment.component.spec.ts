import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { of } from 'rxjs';
import { CommentsConfiguratorService } from '../../services/comments-configurator';
import { CommentComponent } from './comment.component';
import { LocalTimePipe } from './local-time.pipe';

const mockComment = {
    message: 'This is a mock comment',
    createdAt: '2024-02-27T12:00:00Z',
    fullname: 'John Doe',
    uuid: '123456',
    crf: 'ABC123',
    isUpdated: false,
    readonly: false,
};

const mockCommentTranslations = {
    update: 'Update',
    edit: 'Edit',
    remove: 'Remove',
    updated: 'Updated',
};

const mockCommentsConfiguratorService = {
    getAccomplishing: jest.fn().mockReturnValue(of(null)),
    commentAction: jest.fn(),
};

describe('CommentComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(CommentComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA], declarations: [LocalTimePipe] },
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

    describe('when readonly is false', () => {
        it('should render meta inputs with proper values', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: mockComment,
                translations: mockCommentTranslations,
            });
            const crfInput = host.queryCss('input[name=_token]');
            const uuidInput = host.queryCss('input[name=uuid]');

            expect(crfInput.properties.value).toBe(mockComment.crf);
            expect(uuidInput.properties.value).toBe(mockComment.uuid);
        });

        it('should render message area', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: mockComment,
                translations: mockCommentTranslations,
            });
            const textAreaComponent = host.queryCss('spy-textarea');
            const messageComponent = host.queryCss('.mp-comment__message');

            expect(textAreaComponent).not.toBeTruthy();
            expect(messageComponent.nativeElement.textContent).toBe(mockComment.message);
        });

        it('should switch message to textarea area', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: mockComment,
                translations: mockCommentTranslations,
            });
            const linkSwitcher = host.queryCss('spy-button:first-of-type');

            linkSwitcher.nativeElement.click();
            await host.detectChanges();

            const textAreaComponent = host.queryCss('spy-textarea');
            const messageComponent = host.queryCss('.mp-comment__message');

            expect(textAreaComponent.properties.value).toBe(mockComment.message);
            expect(messageComponent).not.toBeTruthy();
        });

        it('should trigger CommentsConfiguratorService.commentAction on update event', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: mockComment,
                translations: mockCommentTranslations,
            });
            const linkSwitcher = host.queryCss('spy-button:first-of-type');

            linkSwitcher.nativeElement.click();
            await host.detectChanges();

            const updateLink = host.queryCss('spy-button:first-of-type');

            updateLink.nativeElement.click();
            await host.detectChanges();

            expect(mockCommentsConfiguratorService.commentAction).toHaveBeenCalled();
        });

        it('should rigger CommentsConfiguratorService.commentAction on remove event', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: mockComment,
                translations: mockCommentTranslations,
            });
            const removeLink = host.queryCss('spy-button:nth-of-type(2)');

            removeLink.nativeElement.click();
            await host.detectChanges();

            expect(mockCommentsConfiguratorService.commentAction).toHaveBeenCalled();
        });
    });

    describe('when readonly is true', () => {
        it('should not render meta inputs with proper values', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: { ...mockComment, readonly: true },
                translations: mockCommentTranslations,
            });
            const crfInput = host.queryCss('input[name=_token]');
            const uuidInput = host.queryCss('input[name=uuid]');

            expect(crfInput).not.toBeTruthy();
            expect(uuidInput).not.toBeTruthy();
        });

        it('should render only message component', async () => {
            const host = await createComponentWrapper(createComponent, {
                comment: { ...mockComment, readonly: true },
                translations: mockCommentTranslations,
            });
            const linkSwitcher = host.queryCss('spy-link[icon="edit"]');
            const messageComponent = host.queryCss('.mp-comment__message');

            expect(linkSwitcher).not.toBeTruthy();
            expect(messageComponent).toBeTruthy();
        });
    });

    it('should render signature', async () => {
        const host = await createComponentWrapper(createComponent, {
            comment: mockComment,
            translations: mockCommentTranslations,
        });
        const signature = host.queryCss('.mp-comment__signature-form');

        expect(signature.nativeElement.textContent).toContain(mockComment.fullname);
    });
});
