import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { of } from 'rxjs';
import { CommentsConfiguratorService } from '../../services/comments-configurator';
import { CommentsThreadComponent } from './comments-thread.component';

const mockComment = {
    message: 'This is a mock comment',
    createdAt: '2024-02-27T12:00:00Z',
    fullname: 'John Doe',
    crf: 'ABC123',
    isUpdated: false,
    readonly: false,
};

const mockComments = Array.from({ length: 3 }, (_, i) => ({
    ...mockComment,
    uuid: i.toString(),
}));

const mockAddComment = {
    crf: 'mockCrf',
    ownerId: 'mockOwnerId',
    ownerType: 'mockOwnerType',
};

const mockActions = {
    create: {
        url: '/comment-merchant-portal-gui/comment/create',
    },
    update: {
        url: '/comment-merchant-portal-gui/comment/update',
        label: 'Update',
    },
    remove: {
        url: '/comment-merchant-portal-gui/comment/delete',
        label: 'Remove',
    },
    edit: {
        label: 'Edit',
    },
};

const mockCommentTranslations = {
    updated: 'Updated',
};

const mockCommentsConfiguratorService = {
    getComments: jest.fn().mockReturnValue(of(mockComments)),
    getError: jest.fn().mockReturnValue(of(null)),
    setInitial: jest.fn(),
};

describe('CommentsThreadComponent', () => {
    const { testModule, createComponent: _createComponent } = getTestingForComponent(CommentsThreadComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    const createComponent = () =>
        createComponentWrapper(_createComponent, {
            comments: mockComments,
            translations: mockCommentTranslations,
            actions: mockActions,
            add: mockAddComment,
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
        TestBed.inject(CommentsConfiguratorService).setInitial(mockComments);
    });

    it('should render mp-add-comment', async () => {
        const host = await createComponent();
        const addComment = host.queryCss('mp-add-comment');

        expect(addComment).toBeTruthy();
        expect(addComment.properties.addComment).toEqual(mockAddComment);
        expect(addComment.properties.addUrl).toBe(mockActions.create.url);
    });

    it('should render mp-comment', async () => {
        const host = await createComponent();
        const comments = host.fixture.debugElement.queryAll(By.css('mp-comment'));

        comments.forEach((comment, i) => {
            expect(comment.properties.comment).toEqual(mockComments[i]);
            expect(comment.properties.updateUrl).toBe(mockActions.update.url);
            expect(comment.properties.removeUrl).toBe(mockActions.remove.url);
            expect(comment.properties.translations).toEqual({
                update: 'Update',
                edit: 'Edit',
                remove: 'Remove',
                updated: 'Updated',
            });
        });
    });
});
