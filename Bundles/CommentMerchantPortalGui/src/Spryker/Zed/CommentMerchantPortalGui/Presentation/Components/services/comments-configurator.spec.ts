/* eslint-disable camelcase */
import { Injectable, NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { CommentsConfiguratorService } from './comments-configurator';
import { provideHttpClient } from '@angular/common/http';

@Injectable()
class MockInjector {
    get = jest.fn();
}

const mockComment = {
    message: 'This is a mock comment',
    createdAt: '2024-02-27T12:00:00Z',
    fullname: 'John Doe',
    crf: 'ABC123',
    isUpdated: false,
    readonly: false,
};

const mockApiComment = {
    comment: {
        message: mockComment.message,
        readonly: false,
        is_updated: false,
        created_at: '2024-02-27T12:00:00Z',
        user: {
            first_name: 'John',
            last_name: 'Doe',
        },
    },
    csrfToken: 'ABC123',
};

const mockComments = Array.from({ length: 3 }, (_, i) => ({
    ...mockComment,
    uuid: i.toString(),
}));

describe('CommentsConfiguratorService', () => {
    let service: CommentsConfiguratorService;
    let injector: MockInjector;
    let httpTestingController: HttpTestingController;

    beforeEach(() => {
        TestBed.configureTestingModule({
            providers: [provideHttpClient(), provideHttpClientTesting(), MockInjector, CommentsConfiguratorService],
            schemas: [NO_ERRORS_SCHEMA],
            teardown: { destroyAfterEach: false },
        });

        service = TestBed.inject(CommentsConfiguratorService);
        injector = TestBed.inject(MockInjector);
        httpTestingController = TestBed.inject(HttpTestingController);
        injector.get.mockImplementation(() => {
            return { reset: () => null };
        });
    });

    afterEach(() => {
        httpTestingController.verify();
    });

    it('should return initial comments data', () => {
        const callback = jest.fn();
        service.getComments().subscribe(callback);
        service.setInitial(mockComments);

        expect(callback).toHaveBeenCalledWith(mockComments);
    });

    it('should return comments data with new created comment', () => {
        const callback = jest.fn();
        service.getComments().subscribe(callback);
        service.setInitial(mockComments);

        expect(callback).toHaveBeenCalledWith(mockComments);

        const form = new FormData();
        const newApiComponent = { ...mockApiComment, comment: { ...mockApiComment.comment, uuid: 'new' } };
        form.append('uuid', newApiComponent.comment.uuid);

        service.commentAction({ form, url: '/create', type: 'create' }, injector);
        const httpResponse = httpTestingController.expectOne('/create');
        httpResponse.flush(newApiComponent);
        expect(callback).toHaveBeenCalledWith([
            ...mockComments,
            { ...mockComment, uuid: newApiComponent.comment.uuid },
        ]);
    });

    it('should return comments data with updated comment', () => {
        const callback = jest.fn();
        service.getComments().subscribe(callback);
        service.setInitial(mockComments);

        expect(callback).toHaveBeenCalledWith(mockComments);

        const form = new FormData();
        const newApiComponent = {
            ...mockApiComment,
            comment: { ...mockApiComment.comment, uuid: '0', message: 'new message' },
        };
        form.append('uuid', newApiComponent.comment.uuid);

        service.commentAction({ form, url: '/update', type: 'update' }, injector);
        const httpResponse = httpTestingController.expectOne('/update');
        httpResponse.flush(newApiComponent);

        expect(callback).toHaveBeenCalledWith([
            { ...mockComment, uuid: '0', message: 'new message' },
            ...mockComments.filter((_, index) => index !== 0),
        ]);
    });

    it('should return comments data with removed comment', () => {
        const callback = jest.fn();
        service.getComments().subscribe(callback);
        service.setInitial(mockComments);

        expect(callback).toHaveBeenCalledWith(mockComments);

        const form = new FormData();
        const newApiComponent = { ...mockApiComment, comment: { ...mockApiComment.comment, uuid: '0' } };
        form.append('uuid', newApiComponent.comment.uuid);

        service.commentAction({ form, url: '/remove', type: 'remove' }, injector);
        const httpResponse = httpTestingController.expectOne('/remove');
        httpResponse.flush(newApiComponent);

        expect(callback).toHaveBeenCalledWith(mockComments.filter((_, index) => index !== 0));
    });
});
