import { TestBed } from '@angular/core/testing';

import { LendBookService } from './lend-book.service';

describe('LendBookService', () => {
  let service: LendBookService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LendBookService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
